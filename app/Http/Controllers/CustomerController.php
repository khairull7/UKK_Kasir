<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function searchByPhoneNumber(Request $request)
    {
        $phoneNumber = $request->get('no_telp');
        
        $customer = Customer::where('no_telp', $phoneNumber)->first();
    
        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }
    
        return response()->json([
            'success' => false
        ]);
    }
}
