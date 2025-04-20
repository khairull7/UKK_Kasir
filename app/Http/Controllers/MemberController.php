<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function verify(Request $request)
    {
        // Store purchase data in session
        session(['purchase_data' => $request->all()]);
        
        return response()->json([
            'success' => true,
            'redirect' => route('member-info.show')
        ]);
    }

    public function show()
    {
        $purchaseData = session('purchase_data');
        
        if (!$purchaseData) {
            return redirect()->route('pembelian.create');
        }

        return view('member-info.show', [
            'selectedProducts' => $purchaseData['items'] ?? [],
            'total' => $purchaseData['total_amount'] ?? 0,
            'points' => 0 // You can implement your point system logic here
        ]);
    }
}