<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    // public function index()
    // {
    //     // Replace these with actual data retrieval logic
    //     $totalSales = 38; // Example value, replace with actual query
    //     $lastUpdated = now()->format('d M Y H:i'); // Current timestamp, or replace with actual last updated time.
    
    //     return view('petugas.dashboard', compact('totalSales', 'lastUpdated'));
    // }
    public function index()
    {
        // Hitung total penjualan yang terjadi hari ini
        $totalSales = Sale::whereDate('created_at', now()->toDateString())->count();
        
        // Waktu terakhir diperbarui (misalnya, waktu saat ini)
        $lastUpdated = now()->format('d M Y H:i');
    
        return view('petugas.dashboard', compact('totalSales', 'lastUpdated'));
    }
}

