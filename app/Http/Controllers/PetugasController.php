<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PetugasController extends Controller
{

        public function index()
    {
        $totalSales = Sale::whereDate('sale_date', Carbon::now()->toDateString())->count();
        
        $lastUpdatedSale = Sale::latest('sale_date')->first();
        $lastUpdated = $lastUpdatedSale ? Carbon::parse($lastUpdatedSale->sale_date)->format('d M Y H:i') : '-';

        return view('petugas.dashboard', compact('totalSales', 'lastUpdated'));
    }

}

