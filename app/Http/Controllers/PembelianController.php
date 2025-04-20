<?php
namespace App\Http\Controllers;

use App\Models\Pembelians;
use App\Models\Pembayaran;
use App\Models\Product;
use App\Models\Member;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PembeliansExport;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelians::with(['details.product'])
            ->select('id', 'customer_name', 'tanggal', 'grand_total', 'dibuat_oleh', 'invoice_number')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('customer_name', 'like', "%{$searchTerm}%")
                  ->orWhere('invoice_number', 'like', "%{$searchTerm}%")
                  ->orWhere('dibuat_oleh', 'like', "%{$searchTerm}%");
            });
        }

        // Per page setting
        $perPage = $request->get('per_page', 10);
        $pembelians = $query->paginate($perPage);

        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $products = Product::all();
        return view('pembelian.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
        ]);
    
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            if ($product->stok < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
        
            $total_price = $product->harga * $request->quantity;
        
            $pembelian = Pembelians::create([
                'customer_name' => $request->customer_name,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
                'grand_total' => $total_price,
                'tanggal' => now(),
                'dibuat_oleh' => 'Petugas'
            ]);
        
            // Create detail record
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'id_produk' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $total_price
            ]);
        
            $product->decrement('stok', $request->quantity);
        
            DB::commit();
            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detail(Pembelians $pembelian)
    {
        $pembelian->load(['details.product']);
        
        if ($pembelian->customer_name !== 'Non Member') {
            $member = Member::where('name', $pembelian->customer_name)->first();
            if ($member) {
                $member->member_since = \Carbon\Carbon::parse($member->member_since);
            }
            $pembelian->member = $member;
        }
        
        return view('pembelian.detail', compact('pembelian'));
    }

    public function confirm(Request $request)
    {
        $selectedProducts = [];
        $total = 0;
    
        foreach($request->quantities as $productId => $quantity) {
            if($quantity > 0) {
                $product = Product::find($productId);
                $subtotal = $product->harga * $quantity;
                $selectedProducts[] = [
                    'id' => $productId,
                    'name' => $product->nama_produk,
                    'price' => $product->harga,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }
    
        if (empty($selectedProducts)) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk');
        }
    
        // Hitung dan simpan poin baru (1% dari total)
        $points_earned = 0;
        if ($request->phone_number) {
            $points_earned = floor($total * 0.01);
            $member = Member::where('phone_number', $request->phone_number)->first();
            
            if ($member) {
                DB::transaction(function() use ($member, $points_earned) {
                    $member->points += $points_earned;
                    $member->save();
                });
            }
        }
    
        return view('pembelian.confirm', compact('selectedProducts', 'total', 'points_earned'));
    }

    public function memberInfo(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'total_bayar' => 'required|numeric',
            'products' => 'required'
        ]);
        
        $selectedProducts = json_decode($request->products, true);
        $total = $request->total_amount;
        $total_bayar = $request->total_bayar;
        
        // Cek member berdasarkan nomor telepon
        $member = Member::where('phone_number', $request->phone_number)->first();
        $memberName = $member ? $member->name : '';
        $existingPoints = $member ? $member->points : 0;  // Poin yang ada di database
        
        // Poin yang akan didapat (1% dari total)
        $points = floor($total * 0.01);
        
        // Periksa apakah member baru
        $isNewMember = !$member;
        
        return view('pembelian.member-info', [
            'products' => $request->products,
            'total_amount' => $total,
            'total_bayar' => $total_bayar,
            'phone_number' => $request->phone_number,
            'points' => $points,
            'memberName' => $memberName,
            'existingPoints' => $existingPoints,  // Pastikan data poin diteruskan
            'isNewMember' => $isNewMember
        ]);
    }
    

    public function pembayaran(Request $request)
    {
        $selectedProducts = json_decode($request->products, true);
        $total = $request->total_amount;
        $total_bayar = $request->total_bayar;
        
        // Handle member data
        $member = null;
        $points_earned = 0;
        $points_used = 0;
        $discount_from_points = 0;

        if ($request->member_type === 'member') {
            $member = Member::where('phone_number', $request->phone_number)->first();
            
            if (!$member) {
                // Create new member
                $member = new Member();
                $member->name = $request->member_name;
                $member->phone_number = $request->phone_number;
                $member->points = floor($total * 0.01); // 1% dari total
                $member->member_since = now();
                $member->save();
                
                $points_earned = floor($total * 0.01);
            } else {
                // Handle existing member points
                if ($request->use_points && $member->points > 0) {
                    $points_used = $member->points;
                    $discount_from_points = $points_used;
                    
                    // Hitung poin baru dari transaksi ini (1%)
                    $points_earned = floor($total * 0.01);
                    
                    // Simpan perubahan poin ke database
                    DB::transaction(function() use ($member, $points_earned) {
                        // Reset poin yang digunakan
                        $member->points = 0;
                        $member->save();
                        
                        // Tambahkan poin baru
                        $member->points = $points_earned;
                        $member->save();
                    });
                    
                } else {
                    // Jika tidak menggunakan poin, tambahkan poin baru
                    $points_earned = floor($total * 0.01);
                    $member->points += $points_earned;
                    $member->save();
                }
            }
        }

        // Calculate final total after point discount
        $final_total = $total - $discount_from_points;
        $kembalian = $total_bayar - $final_total;
        $invoice_number = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        // Create transaction record
        $pembelian = Pembelians::create([
            'invoice_number' => $invoice_number,
            'customer_name' => $member ? $member->name : 'Non Member',
            'grand_total' => $final_total,
            'tanggal' => now(),
            'dibuat_oleh' => 'Petugas',
            'is_member' => $member ? true : false,
            'member_id' => $member ? $member->id : null,
            'poin_digunakan' => $points_used
        ]);

        // Create detail records
        foreach($selectedProducts as $product) {
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'id_produk' => $product['id'],
                'quantity' => $product['quantity'],
                'total_price' => $product['subtotal']
            ]);

            Product::where('id', $product['id'])
                ->decrement('stok', $product['quantity']);
        }

        // Create payment record
        Pembayaran::create([
            'pembelian_id' => $pembelian->id,
            'jumlah_bayar' => $total_bayar,
            'kembalian' => $kembalian
        ]);

        return view('pembelian.pembayaran', compact(
            'selectedProducts',
            'total',
            'final_total',
            'total_bayar',
            'kembalian',
            'invoice_number',
            'member',
            'points_earned',
            'points_used',
            'discount_from_points',
            'pembelian'
        ));
    }

    public function pembayaranNonMember(Request $request)
    {
        $selectedProducts = json_decode($request->products, true);
        $total = $request->total_amount;
        $total_bayar = $request->total_bayar;
        $kembalian = $total_bayar - $total;
        $invoice_number = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        // Create main transaction record
        $pembelian = Pembelians::create([
            'invoice_number' => $invoice_number,
            'customer_name' => 'Non Member',
            'grand_total' => $total,
            'tanggal' => now(),
            'dibuat_oleh' => 'Petugas',
            'is_member' => false
        ]);

        // Create detail records
        foreach($selectedProducts as $product) {
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'id_produk' => $product['id'],
                'quantity' => $product['quantity'],
                'total_price' => $product['subtotal']
            ]);

            Product::where('id', $product['id'])
                ->decrement('stok', $product['quantity']);
        }

        // Create payment record
        Pembayaran::create([
            'pembelian_id' => $pembelian->id,
            'jumlah_bayar' => $total_bayar,
            'kembalian' => $kembalian
        ]);

        return view('pembelian.pembayaran', compact(
            'selectedProducts',
            'total',
            'total_bayar',
            'kembalian',
            'invoice_number',
            'pembelian'
        ));
    }

    public function checkMember($phone)
    {
        $member = Member::where('phone_number', $phone)->first();
        
        if ($member) {
            return response()->json([
                'exists' => true,
                'member' => [
                    'name' => $member->name,
                    'points' => $member->points,
                    'phone_number' => $member->phone_number
                ]
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function exportToExcel()
    {
        return Excel::download(new PembeliansExport, 'pembelian.xlsx');
    }

    public function exportToPDF($id)
    {
        $pembelian = Pembelians::with(['details.product'])->findOrFail($id);
        $member = Member::where('name', $pembelian->customer_name)->first();

        $data = [
            'pembelian' => $pembelian,
            'member' => $member,
        ];

        $pdf = Pdf::loadView('pembelian.pdf_invoice', $data);
        return $pdf->download('invoice_' . $pembelian->invoice_number . '.pdf');
    }
}