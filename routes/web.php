<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthPasienController;
use App\Http\Controllers\AuthRegisterController;
use App\Http\Controllers\PendaftaranPoliController;
use App\Http\Controllers\Pasien\DashboardController;
use App\Http\Controllers\Pasien\AntrianController;
use App\Http\Controllers\Pasien\RekamMedisController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\Profil\ProfilJadwalDokterController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', [ProfileController::class, 'index'])->name('home');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

Route::view('/fasilitas', 'profile.fasilitas')->name('fasilitas');
Route::view('/contact', 'profile.contact')->name('contact');

Route::get('/profil/jadwal-dokter',
    [ProfilJadwalDokterController::class, 'index']
)->name('profil.jadwal_dokter');


Route::get('/pendaftaran-online', function () {
    return view('pasien.pendaftaran-poliklinik');
})->name('pendaftaran.online');


/*
|--------------------------------------------------------------------------
| 2. AUTH PASIEN
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthPasienController::class,'index'])->name('login');
Route::post('/login', [AuthPasienController::class,'login'])->name('login.process');

Route::get('/register', [AuthRegisterController::class,'index'])->name('register');
Route::post('/register', [AuthRegisterController::class,'register'])->name('register.process');

Route::post('/logout', [AuthPasienController::class,'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 3. PASIEN JKN (TIDAK PERLU LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/pendaftaran-poliklinik/jkn', function () {
    return view('pasien.pendaftaran-jkn');
})->name('pendaftaran.jkn');

Route::post('/pendaftaran-poliklinik/jkn',
    [PendaftaranPoliController::class,'storeJkn']
)->name('pendaftaran.jkn.store');


/*
|--------------------------------------------------------------------------
| 4. PASIEN UMUM (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard',
        [DashboardController::class,'index']
    )->name('dashboard');

    Route::get('/pendaftaran-poliklinik/umum', function () {
        return view('pasien.pendaftaran-umum');
    })->name('pendaftaran.umum');

    Route::post('/pendaftaran-poliklinik/umum',
        [PendaftaranPoliController::class,'storeUmum']
    )->name('pendaftaran.umum.store');

    Route::get('/pasien/antrian',
        [AntrianController::class,'index']
    )->name('pasien.antrian');

    Route::get('/pasien/rekam-medis',
        [RekamMedisController::class,'index']
    )->name('pasien.rekammedis');

});


/*
|--------------------------------------------------------------------------
| 5. PAYMENT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){

Route::get('/payment/{id}', [PaymentController::class,'pay'])
    ->name('payment.pay');

});

Route::post('/payment/callback', [CallbackController::class, 'handle']);
Route::get('/payment/finish', function () {
    return redirect('/dashboard')->with('success','Pembayaran berhasil');
});

Route::get('/payment/error', function () {
    return redirect('/dashboard')->with('error','Pembayaran gagal');
});


// rekam medis 

Route::middleware(['auth'])->group(function () {

    // Route::get('/pasien/rekam-medis', [RekamMedisController::class, 'index'])
    //     ->name('pasien.rekammedis');

    Route::get('/pasien/rekam-medis/pdf/{id}', [RekamMedisController::class, 'pdf'])
        ->name('pasien.rekammedis.pdf');

});
