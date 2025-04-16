<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Dummy data untuk grafik
        $dates = ['13 March 2025', '14 March 2025', '15 March 2025', '16 March 2025', '17 March 2025'];
        $sales = [10, 15, 5, 25, 30];
    
        $productNames = ['Obat', 'Ayam', 'Paracetamol', 'Kipas', 'Red Orchid Flower'];
        $productPercentages = [25, 20, 15, 30, 10];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];
    
        return view('admin.dashboard', compact('dates', 'sales', 'productNames', 'productPercentages', 'colors'));
    }
    
}

