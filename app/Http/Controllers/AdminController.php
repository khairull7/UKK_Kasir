<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        //  Penjualan per hari (kuantitas)
        $dailySalesQuantity = DB::table('detail_sales')
            ->join('sales', 'detail_sales.sale_id', '=', 'sales.id')
            ->select(
                DB::raw('DATE(sales.sale_date) as date'),
                DB::raw('SUM(detail_sales.amount) as total_daily_quantity')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailySalesQuantityDates = $dailySalesQuantity->pluck('date')->toArray();
        $dailySalesQuantities = $dailySalesQuantity->pluck('total_daily_quantity')->toArray();

        //  Total produk yang terjual
        $totalProductsSold = DB::table('detail_sales')
            ->sum('amount');

        //  Total pendapatan (revenue) per hari
        $salesData = DB::table('sales')
            ->select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total_price) as total_sales'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $salesData->pluck('date')->toArray();
        $sales = $salesData->pluck('total_sales')->toArray();

        // Produk Terlaris
        $productSales = DB::table('detail_sales')
            ->join('products', 'detail_sales.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(detail_sales.amount) as total_sold'))
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->get();

        $productNames = $productSales->pluck('name')->toArray();
        $productPercentages = $productSales->map(function ($item) use ($totalProductsSold) {
            return $totalProductsSold > 0 ? round(($item->total_sold / $totalProductsSold) * 100, 2) : 0;
        })->toArray();
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];


        return view('admin.dashboard', compact(
            'dailySalesQuantityDates',
            'dailySalesQuantities',
            'totalProductsSold',
            'sales',
            'dates',
            'productNames',
            'productPercentages',
            'colors'
        ));
    }
    // public function index()
    // {
    //     $salesData = DB::table('sales')
    //         ->select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total_price) as total_sales'))
    //         ->groupBy('date')
    //         ->orderBy('date')
    //         ->get();

    //     $dates = $salesData->pluck('date')->toArray();
    //     $sales = $salesData->pluck('total_sales')->toArray();

    //     $productSales = DB::table('detail_sales')
    //         ->join('products', 'detail_sales.product_id', '=', 'products.id')
    //         ->select('products.name', DB::raw('SUM(detail_sales.amount) as total_sold'))
    //         ->groupBy('products.name')
    //         ->get();

    //     $totalSold = $productSales->sum('total_sold');
    //     $productNames = $productSales->pluck('name')->toArray();
    //     $productPercentages = $productSales->map(function ($item) use ($totalSold) {
    //         return round(($item->total_sold / $totalSold) * 100, 2);
    //     })->toArray();

    //     $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']; 

    //     return view('admin.dashboard', compact('dates', 'sales', 'productNames', 'productPercentages', 'colors'));
    // }




}

