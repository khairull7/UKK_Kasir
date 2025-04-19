<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DetailSaleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\SaleControllerr;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login'); 
});

// Halaman Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Proses Login
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Users Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export'); 
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Sales Routes
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{id}/edit', [SaleController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{id}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::get('/sales/{id}/pdf', [SaleController::class, 'downloadPdf'])->name('sales.pdf');
    Route::get('/sales/export/excel', [SaleController::class, 'exportExcel'])->name('sales.export.excel');
    
    // Products Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
});



// Petugas Routes
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index'); 
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', action: [SaleController::class, 'store'])->name('sales.store');
    Route::post('sales/confirm', [SaleController::class, 'confirm'])->name('sales.confirm');  
    Route::post('/sales/check-member',  [SaleController::class, 'checkMember'])->name('sales.member-info');
    Route::post('/sales/non-member/payment', [SaleController::class, 'paymentNonMember'])->name('sales.non-member.payment');
    Route::get('sales/{id}/payment', [SaleController::class, 'payment'])->name('sales.payment');
    Route::get('/check-member/{phone}', [SaleController::class, 'checkMember']);
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/export/excel', [SaleController::class, 'exportExcel'])->name('sales.export.excel');
    Route::get('/sales/{id}/pdf', [SaleController::class, 'downloadPdf'])->name('sales.pdf');
});

