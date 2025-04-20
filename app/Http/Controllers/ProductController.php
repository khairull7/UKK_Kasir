<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        
        $products = Product::when($search, function($query) use ($search) {
                $query->where('nama_produk', 'like', '%' . $search . '%')
                      ->orWhere('harga', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate($perPage);
        
        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('products', 'public');
            $validated['img'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('img')) {
            // Delete old image
            if ($product->img) {
                Storage::disk('public')->delete($product->img);
            }
            // Store new image
            $imagePath = $request->file('img')->store('products', 'public');
            $validated['img'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stok' => 'required|numeric|min:0'
        ]);
    
        $product->update([
            'stok' => $request->stok
        ]);
    
        return redirect()->back()->with('success', 'Stock updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

        public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}

