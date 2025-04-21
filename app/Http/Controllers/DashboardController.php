<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Models\Product;
use App\Models\Pembelians;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        $totalProducts = Product::count();
        $totalMembers = Member::count(); 
        $totalPembelian = Pembelians::count();
        
        // Calculate total income from pembelian_details
        $totalPendapatan = DB::table('pembelian_details')
            ->join('products', 'pembelian_details.id_produk', '=', 'products.id')
            ->sum(DB::raw('pembelian_details.quantity * products.harga'));
    
        $todaySales = Pembelians::whereDate('created_at', today())->count();
    
        $dailySales = Pembelians::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        $productSales = DB::table('pembelian_details')
            ->join('products', 'pembelian_details.id_produk', '=', 'products.id')
            ->select('products.nama_produk', DB::raw('SUM(pembelian_details.quantity) as total_sold'))
            ->groupBy('products.nama_produk')
            ->orderByDesc('total_sold')
            ->get();
            
        $nama_produk = $productSales->pluck('nama_produk')->toArray();
        $total_penjualan = $productSales->sum('total_sold');
        $productPercentages = $productSales->map(function($item) use ($total_penjualan) {
            return $total_penjualan > 0 ? round(($item->total_sold / $total_penjualan) * 100, 1) : 0;
        })->toArray();

        $colors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(255, 99, 255, 0.8)',
            'rgba(54, 162, 64, 0.8)',
            'rgba(255, 206, 192, 0.8)',
            'rgba(75, 192, 255, 0.8)',
            'rgba(105, 105, 105, 0.8)',  
            'rgba(255, 165, 0, 0.8)',  
            'rgba(0, 128, 0, 0.8)',    
            'rgba(0, 0, 255, 0.8)',   
            'rgba(128, 0, 128, 0.8)', 
            'rgba(255, 20, 147, 0.8)',
            'rgba(255, 140, 0, 0.8)',  
            'rgba(255, 0, 0, 0.8)',    
            'rgba(0, 255, 255, 0.8)', 
            'rgba(255, 69, 0, 0.8)',   
            'rgba(138, 43, 226, 0.8)', 
        ];

        $actualData = $productSales->pluck('total_sold')->toArray();

        return view('dashboard', compact(
            'todaySales', 
            'dailySales', 
            'productSales',
            'nama_produk',
            'productPercentages',
            'colors',
            'actualData',
            'totalProducts',
            'totalMembers',
            'totalPembelian',
            'totalPendapatan'
        ));
    }
}