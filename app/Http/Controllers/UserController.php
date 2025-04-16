<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Menampilkan daftar semua pengguna
    public function index()
    {
        $users = User::all();  // Ambil semua pengguna
        return view('users.index', compact('users'));
    }

    // Menampilkan form untuk menambahkan pengguna baru
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan pengguna baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Password harus ada konfirmasi
            'role' => 'required|in:admin,petugas', // Role harus admin atau petugas
        ]);

        // Membuat pengguna baru
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->role = $validated['role']; 
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit data pengguna
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Menyimpan perubahan data pengguna
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,petugas',
        ]);


        // Update data pengguna
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($validated['password']) {
            $user->password = bcrypt($validated['password']);
        }
        $user->role = $validated['role'];
        $user->save();

        return redirect()->route(route: 'admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // Menghapus pengguna
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
