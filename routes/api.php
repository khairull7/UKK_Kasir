<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// <?php

// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\DetailSaleController;
// use App\Http\Controllers\LoginController;
// use App\Http\Controllers\PetugasController;
// use App\Http\Controllers\SaleControllerr;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\PurchaseController;
// use App\Http\Controllers\SaleController;
// use App\Http\Controllers\TransactionController;
// use App\Http\Controllers\UserController;
// use App\Models\Transaction;

// /*
// |---------------------------------------------------------------------------
// | Web Routes
// |---------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider and all of them will be
// | assigned to the "web" middleware group. Make something great!
// |
// */

// Route::get('/', function () {
//     return redirect()->route('login'); 
// });

// // Halaman Login
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// // Proses Login
// Route::post('/login', [LoginController::class, 'login']);

// // Logout
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

// // Admin & Petugas Routes
// Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
//     // Users Routes (Admin Only)
//     Route::middleware('role:admin')->group(function () {
//         Route::get('/users', [UserController::class, 'index'])->name('users.index');
//         Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
//         Route::post('/users', [UserController::class, 'store'])->name('users.store');
//         Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
//         Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
//         Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
//         Route::get('/users/export', [UserController::class, 'export'])->name('users.export'); 
//         Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
//         Route::get('/transactions/export/excel', action: [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
//     });

//     // Transaction Routes (For both Admin and Petugas)
//     Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
//     Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
//     Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
//     Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
//     Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
//     Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
//     Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
//     Route::get('/transactions/{id}/pdf', [TransactionController::class, 'downloadPdf'])->name('transactions.pdf');
//     Route::get('/transactions/export/excel', action: [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
    
//     // Product Routes (For both Admin and Petugas)
//     Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//     Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
//     Route::post('/products', [ProductController::class, 'store'])->name('products.store');
//     Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
//     Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
//     Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
//     Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
//     Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
// });

