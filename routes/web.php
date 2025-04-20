<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Exports\PembeliansExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', function () {
    return view('login');
})->name('login');


// Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //User
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', controller: UserController::class);
        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/users', [UserController::class, 'store'])->name('user.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show'); 
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('/users/export', [UserController::class, 'export'])->name('user.export');
    });
    //Product
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::middleware(['admin'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    });

    //Pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::middleware(['staff'])->group(function () {
        Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::post('/pembelian/confirm', [PembelianController::class, 'confirm'])->name('pembelian.confirm');
        Route::post('/pembelian/member-info', [PembelianController::class, 'memberInfo'])->name('pembelian.member-info');
        Route::post('/pembelian/pembayaran', [PembelianController::class, 'pembayaran'])->name('pembelian.pembayaran');
        Route::post('/pembelian/non-member/pembayaran', [PembelianController::class, 'pembayaranNonMember'])->name('pembelian.non-member.pembayaran');
    });
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::get('/check-member/{phone}', [PembelianController::class, 'checkMember']);
    Route::get('/pembelian/detail/{pembelian}', [PembelianController::class, 'detail'])->name('pembelian.detail');
    Route::get('/pembelian/{id}', [DetailPembelianController::class, 'ajaxDetailHTML']);
    Route::get('/export/pembelian', [PembelianController::class, 'exportToExcel'])->name('pembelian.export');
    Route::get('/pembelian/{id}/export-pdf', [PembelianController::class, 'exportToPDF'])->name('pembelian.export_pdf');

});



