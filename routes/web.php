<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DownloadController;
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

//payment
Route::post('payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::get('/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
Route::get('/payment_confirmation/{id}', [PaymentController::class, 'redirectPage'])->name('redirectpage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/artisan/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return response()->json(['message' => 'Optimize clear completed.', 'output' => Artisan::output()]);
})->name('artisan.optimize-clear');

require __DIR__.'/auth.php';

Route::get('airtime', [AirtimeController::class, 'index'])->name('airtime');
Route::post('airtime/store', [AirtimeController::class, 'store'])->name('airtime.store');


//for displaying list of data
Route::get('data', [DataController::class, 'index'])->name('data');
Route::post('data/validate', [DataController::class, 'validateValues'])->name('data.validate');
Route::post('data/update_table', [DataController::class, 'updateTable'])->name('data.updateTable');
Route::post('data/store', [DataController::class, 'store'])->name('data.store');
