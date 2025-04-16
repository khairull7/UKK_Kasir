<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
                abort(403, 'Akses ditolak');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Menyaring produk berdasarkan pencarian
        if ($search) {
            $products = Product::where('name', 'like', '%' . $search . '%')->get();
        } else {
            $products = Product::all();
        }

        return view('products.index', compact('products', 'search'));
    }

    

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $validated['name'],
            'image' => $imagePath,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        return redirect()->route(Auth::user()->role . '.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->save();

        return redirect()->route(Auth::user()->role . '.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function updateStock(Request $request, Product $product)
    {
        // Validasi input
        $validated = $request->validate([
            'stock' => 'required|integer|min:0', // Validasi untuk stok
        ]);
    
        // Update stok produk
        $product->stock = $validated['stock'];
        $product->save();
    
        // Menyimpan pesan sukses di session dengan nama produk
        return redirect()->route(Auth::user()->role . '.products.index')
                         ->with('success', 'Stok produk "' . $product->name . '" berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route(Auth::user()->role . '.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
