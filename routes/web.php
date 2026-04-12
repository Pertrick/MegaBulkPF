<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\BulkRetryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/how-to-use', function () {
    return view('how-to-use');
})->name('how-to-use');

//for downloading sample file

Route::get('/download_data', [DownloadController::class, 'downloadData'])->name('download.data');
Route::get('/download_airtime', [DownloadController::class, 'downloadAirtime'])->name('download.airtime');

// payment: Korapay return URL (checkout is initiated from data/store and airtime/store).
Route::get('/verify', [PaymentController::class, 'verifyPayment'])
    ->middleware('throttle:payment.verify')
    ->name('payment.verify');

// Signed URL to reset failed bulk rows and re-queue fulfillment (generate link via `php artisan bulk:retry-failed {id|uuid}`).
Route::get('/bulk/retry/{uuid}', BulkRetryController::class)
    ->middleware('signed')
    ->whereUuid('uuid')
    ->name('bulk.retry');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('airtime', [AirtimeController::class, 'index'])->name('airtime');
Route::post('airtime/import-csv', [AirtimeController::class, 'importCsv'])->name('airtime.importCsv');
Route::get('airtime/bulk-order/{uuid}/preview-rows', [AirtimeController::class, 'bulkOrderPreviewRows'])->name('airtime.bulkOrder.previewRows');
Route::get('airtime/bulk-order/{uuid}', [AirtimeController::class, 'bulkOrderStatus'])->name('airtime.bulkOrder.status');
Route::post('airtime/store', [AirtimeController::class, 'store'])->name('airtime.store');


//for displaying list of data
Route::get('data', [DataController::class, 'index'])->name('data');
Route::post('data/import-csv', [DataController::class, 'importCsv'])->name('data.importCsv');
Route::get('data/bulk-order/{uuid}/preview-rows', [DataController::class, 'bulkOrderPreviewRows'])->name('data.bulkOrder.previewRows');
Route::get('data/bulk-order/{uuid}', [DataController::class, 'bulkOrderStatus'])->name('data.bulkOrder.status');
Route::post('data/validate', [DataController::class, 'validateValues'])->name('data.validate');
Route::post('data/update_table', [DataController::class, 'updateTable'])->name('data.updateTable');
Route::post('data/store', [DataController::class, 'store'])->name('data.store');
