<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $user = Auth::user();
    
    
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard'); 
            } elseif ($user->role == 'petugas') {
                return redirect()->route('petugas.dashboard'); 
            }
    
            return redirect()->intended('/dashboard');
        }
    
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
