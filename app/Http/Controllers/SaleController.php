<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Models\Customer;
use App\Models\DetailSale;
use App\Models\Product;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    //

    
    public function index(Request $request)
   {
        $query = Sale::with('customer', 'staff')->orderBy('created_at', 'desc');

       // Search
       if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->whereHas('customer', function ($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        });
    }

    // Ambil nilai per_page dari request (default 10)
    $perPage = $request->get('per_page', 10);

    // Pagination
    $sales = $query->paginate($perPage)->withQueryString(); // withQueryString biar query tetap ada saat pindah halaman

    return view('sales.index', compact('sales'));
 }


    
    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    public function confirm(Request $request)
    {
        // Debug the incoming request
        // dd($request->all());  // Uncomment this to see what data is being submitted
        
        $selectedProducts = [];
        $total = 0;
        
        // Process the items being sent from the form
        if ($request->has('items')) {
            foreach ($request->items as $productId => $data) {
                $product = Product::findOrFail($productId);
                $quantity = $data['quantity'];
                $subtotal = $product->price * $quantity;
                
                $selectedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                
                $total += $subtotal;
            }
        }
        
        return view('sales.confirm', compact('selectedProducts', 'total'));
    }


    public function store(Request $request)
{

    // dd($request->all());
    $sale = null;
    $selectedProducts = [];
    $totalPrice = 0;
    $customer = null;


    DB::transaction(function () use ($request, &$sale, &$selectedProducts, &$totalPrice, &$customer) {
        $totalPrice = $request->total_price;
        $totalPay = $request->payment_amount ?? 0;
        $usedPoin = $request->used_poin ?? 0;
        $totalReturn = $totalPay - $totalPrice;

        // Cek jika member
        if ($request->member_type === 'member') {
            $customer = Customer::where('no_telp', $request->no_telp)->first();

            // Kalau belum ada, buat customer baru
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $request->name,
                    'no_telp' => $request->no_telp,
                    'poin' => 0,
                ]);
            }

            // Kurangi poin jika digunakan
            if ($usedPoin > 0) {
                $customer->usePoin($usedPoin);
            }
        }

        // Hitung poin yang didapat (1% dari total belanja)
        $earnedPoin = floor($totalPrice * 0.01);

        // Simpan transaksi
        $sale = Sale::create([
            'sale_date' => now(),
            'total_price' => $totalPrice,
            'total_pay' => $totalPay,
            'total_return' => $totalReturn,
            'staff_id' => auth()->id(),
            'customer_id' => $customer ? $customer->id : null,
            'poin' => $earnedPoin,
            'total_poin' => $customer ? $customer->poin : 0,
            'used_poin' => $usedPoin,
        ]);

        // Tambahkan poin ke customer setelah transaksi
        if ($customer) {
            $customer->addPoin($earnedPoin);
        }

        // Simpan detail penjualan dan update stok produk
        foreach ($request->items as $productId => $data) {
            $product = Product::findOrFail($productId);
            $quantity = $data['quantity'];
            $subtotal = $product->price * $quantity;

            DetailSale::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'amount' => $quantity,
                'sub_total' => $subtotal,
            ]);

            Product::where('id', $productId)->decrement('stock', $quantity);

            $selectedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
            // dd($request->all(), $customer);
        }
    });

    return redirect()->route('petugas.sales.payment', $sale->id);
}



    public function show($id)
    {
        $sale = Sale::with(['customer', 'staff', 'details'])->findOrFail($id);
        
        return view('sales.show', compact('sale'));
    }

    public function downloadPdf($id)
    {
        $sale = Sale::with(['customer', 'staff', 'details'])->findOrFail($id);

        $pdf = Pdf::loadView('sales.receipt', compact('sale'));
        return $pdf->stream('bukti-pembelian-' . $sale->id . '.pdf');
    }


        public function exportExcel()
    {
        // Cek apakah ada data penjualan
        $sales = Sale::with('staff')->get();

        if ($sales->isEmpty()) {
            // Menangani jika tidak ada data penjualan
            return redirect()->back()->with('error', 'Tidak ada data penjualan untuk di-export');
        }

        // Jika ada data, lakukan export
        return Excel::download(new SalesExport, 'sales.xlsx');
    }



    public function payment($id)
    {
        $sale = Sale::with(['details', 'customer'])->findOrFail($id);
    
        $selectedProducts = [];
        $totalPrice = 0;
        foreach ($sale->details as $detail) {
            $selectedProducts[] = [
                'name' => $detail->product->name,
                'price' => $detail->product->price,
                'quantity' => $detail->amount,
                'subtotal' => $detail->sub_total,
            ];
            $totalPrice += $detail->sub_total;
        }
    
        $totalReturn = $sale->total_pay - $sale->total_price;
    
        return view('sales.payment', [
            'sale' => $sale,
            'selectedProducts' => $selectedProducts,
            'total' => $totalPrice,
            'totalReturn' => $totalReturn,
            'totalPay' => $sale->total_pay
        ]);
    }

    public function checkMember(Request $request)
    {
        $phone = $request->phone_number;
        $memberType = $request->member_type;
    
        // Kalau bukan member, langsung ke store
        if ($memberType !== 'member') {
            return $this->store($request);
        }
    
        // Ambil total belanja dan total bayar
        $totalBelanja = $request->total_amount;
        $totalBayar = $request->payment_amount;
    
        // Cari customer berdasarkan nomor telepon
        $customer = Customer::where('no_telp', $phone)->first();
    
        // Jika customer tidak ditemukan, set nama dan data lainnya dari form input
        if (!$customer) {
            $customer = new \stdClass();
            $customer->name = $request->name; // Ambil nama dari form
            $customer->phone_number = $phone;
            $customer->poin = 0;
        }
    
        // Tentukan poin yang bisa digunakan
        $earnedPoin = $customer->poin;
        $poinUntukPotongan = min($earnedPoin, $totalBelanja);  // Maksimal poin yang dapat digunakan adalah total belanja
        $totalSetelahPotongan = $totalBelanja - $poinUntukPotongan;  // Total setelah dipotong poin
    
        return view('sales.member-info', [
            'selectedProducts' => $this->prepareSelectedProducts($request),
            'totalBelanja' => $totalBelanja,
            'totalBayar' => $totalBayar,
            'phone_number' => $phone,
            'customer' => $customer,
            'earnedPoin' => $earnedPoin,
            'poinUntukPotongan' => $poinUntukPotongan,
            'totalSetelahPotongan' => $totalSetelahPotongan,
        ]);
    }
    
    

private function prepareSelectedProducts($request)
{
    $selectedProducts = [];

    foreach ($request->items as $productId => $data) {
        $product = Product::findOrFail($productId);
        $quantity = $data['quantity'];
        $subtotal = $product->price * $quantity;

        $selectedProducts[] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }

    return $selectedProducts;
}


}
