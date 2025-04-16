<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailSale;
use App\Models\Product;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    //

    public function index()
    {
        // Memuat relasi 'customer' dengan penjualan
        $sales = Sale::with('customer')->get();
        return view('sales.index', compact('sales'));
    }
    

    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        return view('sales.create', compact('products', 'customers'));
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
    $sale = null;
    $selectedProducts = [];
    $totalPrice = 0;
    $customer = null;

    DB::transaction(function () use ($request, &$sale, &$selectedProducts, &$totalPrice, &$customer) {
        $totalPrice = $request->total_amount;
        $totalPay = $request->payment_amount ?? 0;
        $totalReturn = $totalPay - $totalPrice;

        // Jika member, cek apakah customer sudah ada
        if ($request->member_type === 'member') {
            $customer = Customer::where('no_telp', $request->no_telp)->first();
        
            if (!$customer) {
                // Buat customer baru jika belum ada
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'no_telp' => $request->no_telp,
                    'poin' => $request->total_poin ?? 0,
                ]);
            }
        }
        
        // Simpan transaksi
        $sale = Sale::create([
            'sale_date' => now(),
            'total_price' => $totalPrice,
            'total_pay' => $totalPay,
            'total_return' => $totalReturn,
            'staff_id' => auth()->id(),
            'customer_id' => $customer ? $customer->id : null,  // Customer id disimpan jika ada
            'poin' => $request->poin ?? 0,
            'total_poin' => $request->total_poin ?? 0,
        ]);
        
        
        // Create the sale transaction
        $sale = Sale::create([
            'sale_date' => now(),
            'total_price' => $totalPrice,
            'total_pay' => $totalPay,
            'total_return' => $totalReturn,
            'staff_id' => auth()->id(),
            'customer_id' => $customer ? $customer->id : null,  // Associate customer if exists
            'poin' => $request->poin ?? 0,
            'total_poin' => $request->total_poin ?? 0,
        ]);

        // Save product details
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

            // Decrement product stock
            Product::where('id', $productId)->decrement('stock', $quantity);

            // Collect selected products for confirmation
            $selectedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }
    });

    // Redirect to payment page (invoice)
    return redirect()->route('petugas.sales.payment', $sale->id);
}



    public function show($id)
    {
        $sale = Sale::with(['customer', 'staff', 'details.product'])->findOrFail($id);

        return view('sales.show', compact('sale'));
    }

    public function downloadPdf($id)
    {
        $sale = Sale::with(['customer', 'staff', 'details.product'])->findOrFail($id);

        $pdf = Pdf::loadView('sales.receipt', compact('sale'));
        return $pdf->stream('bukti-pembelian-' . $sale->id . '.pdf');
    }


    public function payment($id)
    {
        // Fetch the sale data using the provided ID
        $sale = Sale::with(['details', 'customer'])->findOrFail($id);

        // Get the selected products and total price calculation
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

        // Calculate the return amount (if any)
        $totalReturn = $sale->total_pay - $sale->total_price;

        return view('sales.payment', [
            'sale' => $sale,
            'selectedProducts' => $selectedProducts,
            'total' => $totalPrice,
            'total_return' => $totalReturn
        ]);
    }

        public function checkMember(Request $request)
    {
        $phoneNumber = $request->input('no_telp');
        $customer = Customer::where('phone', $phoneNumber)->first();
        
        $selectedProducts = session('selected_products', []);
        $total = session('total_amount', 0);
        
        return view('sales.confirm', compact('selectedProducts', 'total', 'customer'));
    }

}
