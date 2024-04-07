<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesAttachmentsController;
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
    return view('auth.login');
})->middleware('guest');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
/**************************************************/
Route::resource('invoices', InvoicesController::class);
Route::put('/status_update/{id}', [InvoicesController::class, 'status_update'])->name('status_update');
Route::get('/invoice_paid', [InvoicesController::class, 'invoice_paid'])->name('invoice_paid');
Route::get('/invoice_unpaid', [InvoicesController::class, 'invoice_unpaid'])->name('invoice_unpaid');
Route::get('/invoice_partial', [InvoicesController::class, 'invoice_partial'])->name('invoice_partial');
Route::get('/invoice_archive', [InvoicesController::class, 'invoice_archive'])->name('invoice_archive');
Route::get('/archive/{id}', [InvoicesController::class, 'softDelete'])->name('archive');
Route::get('/restore/{id}', [InvoicesController::class, 'restore'])->name('invocie.restore');
Route::get('/print_invoice/{id}', [InvoicesController::class, 'print_invoice'])->name('invoice.print');
Route::get('invoice/export/', [InvoicesController::class, 'export'])->name('invoice.export');

/**************************************************/
Route::resource('sections', SectionsController::class);

/**************************************************/
Route::resource('InvoiceAttachments', InvoicesAttachmentsController::class);
/**************************************************/

Route::resource('products', ProductsController::class);

Route::get('section/{id}', [InvoicesController::class, 'getproducts']);

Route::get('InvoicesDetails/{id}', [InvoicesDetailsController::class, 'show'])->name('InvoicesDetails');
Route::get('download/{invoice_number}/{file_name}', [InvoicesAttachmentsController::class, 'download_file'])->name('downloadfile');
Route::get('View_file/{invoice_number}/{file_name}', [InvoicesAttachmentsController::class, 'open_file'])->name('viewfile');
Route::post('delete_file', [InvoicesAttachmentsController::class, 'destroy'])->name('delete_file');


/**************************************************/



Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);

Route::get('/invoices_report', [ReportsController::class, 'index'])->name('report.index');
Route::get('/invoices_customer', [ReportsController::class, 'customer'])->name('report.customer');

});
/**************************************************/

require __DIR__.'/auth.php';

Route::get('/{id}', [AdminController::class, 'index'])->middleware('auth');
