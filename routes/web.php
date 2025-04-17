<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
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
    Route::resource('users', controller: UserController::class);
    Route::resource('sales', controller: SaleController::class);

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{id}/pdf', [SaleController::class, 'downloadPdf'])->name('sales.pdf');
    Route::get('/sales/export/excel', [SaleController::class, 'exportExcel'])->name('sales.export.excel');
});

// Petugas Routes
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index'); 
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('sales/confirm', [SaleController::class, 'confirm'])->name('sales.confirm');  
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/{id}/payment', [SaleController::class, 'payment'])->name('sales.payment');
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{id}/pdf', [SaleController::class, 'downloadPdf'])->name('sales.pdf');
    Route::get('/check-member', [SaleController::class, 'checkMember'])->name('sales.check-member');
    Route::get('/search-member', [CustomerController::class, 'searchByPhoneNumber']);
    Route::get('/sales/export/excel', [SaleController::class, 'exportExcel'])->name('sales.export.excel');

});


