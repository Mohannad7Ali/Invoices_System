<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\customer_report_controller;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\invoicesArchive;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Invoice_ReportsController;
use App\Http\Controllers\Customer_reportController;


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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/invoices', InvoicesController::class);

Route::resource('/sections', SectionController::class);

Route::resource('products' , ProductController::class);

Route::get('products_for_sections/{id}' , [SectionController::class , 'getProducts']);

Route::get('InvoicesDetails/{id}' , [InvoicesController::class , 'show']) ;

Route::get('download/{invoice_number}/{file_name}', [InvoiceAttachmentController::class ,'downloadFile']) ;

Route::get('displayFile/{invoice_number}/{file_name}', [InvoiceAttachmentController::class ,'displayFile']) ;

Route::post('deleteFile', [InvoiceAttachmentController::class ,'deleteFile'])->name('deleteFile') ;

Route::post('InvoiceAttachments' , [InvoiceAttachmentController::class , 'addNewAttachments']);

Route::get('show_status/{id}' , [InvoicesController::class , 'show_status'])->name('show_status');

Route::post('updateStatus/{id}' , [InvoicesController::class , 'updateStatus'])->name('updateStatus');

Route::get('invoice_paid' , [InvoicesController::class , 'show_invoices_paid'])->name('paid');

Route::get('invoice_not_paid' , [InvoicesController::class , 'show_invoices_notPaid'])->name('notPaid');

Route::get('invoice_paid_partial' , [InvoicesController::class , 'show_invoices_partial'])->name('partial');

// Route::get('invoice_Archive' , [InvoicesController::class , 'show_Archive'])->name('Archive');

Route::post('restore_invoice' , [invoicesArchive::class , 'restore'])->name('invoicesArchived.restore');

Route::resource('invoiceArchive' , invoicesArchive::class );

Route::get('print_invoice/{id}', [InvoicesController::class ,'print_invoice']);

Route::group(['middleware' => ['auth']], function() {
    Route::resource('/roles', RoleController::class);
    Route::resource('/users', UserController::class);
});

Route::get('invoice_report' , [Invoice_ReportsController::class , 'index']) ;
Route::post('Search_invoices' , [Invoice_ReportsController::class , 'search']) ;

Route::get('customers_report' , [Customer_reportController::class , 'index']) ;
Route::post('customers_report' , [Customer_reportController::class , 'search']) ;

Route::get('MarkAllAs_read' , [InvoicesController::class , 'MarkAllAsRead']);

Route::get('exportInvoice' , [InvoicesController::class , 'export']);

Route::get('/{page}', [AdminController::class ,'index']);
