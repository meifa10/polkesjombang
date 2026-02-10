<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthPasienController;
use App\Http\Controllers\AuthRegisterController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\PendaftaranPoliController;
use App\Http\Controllers\Pasien\AntrianController;
use App\Http\Controllers\Pasien\RekamMedisController;


/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', [ProfileController::class, 'index'])->name('home');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::view('/fasilitas', 'profile.fasilitas')->name('fasilitas');
Route::view('/contact', 'profile.contact')->name('contact');

/*
|--------------------------------------------------------------------------
| PENDAFTARAN ONLINE
|--------------------------------------------------------------------------
| LANGSUNG PILIH JENIS PASIEN (TANPA LOGIN)
*/
Route::get('/pendaftaran-online', function () {
    return view('pasien.pendaftaran-poliklinik');
})->name('pendaftaran.online');


/*
|--------------------------------------------------------------------------
| PENDAFTARAN POLIKLINIK
|--------------------------------------------------------------------------
| PILIH JENIS PASIEN (TANPA LOGIN)
*/
Route::get('/pendaftaran-poliklinik', function () {
    return view('pasien.pendaftaran-poliklinik');
})->name('pendaftaran.poliklinik');

/*
|--------------------------------------------------------------------------
| FORM JKN (LOGIN BARU DIMINTA)
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran-poliklinik/jkn', function () {

    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('redirect_to', 'pendaftaran.jkn');
    }

    return view('pasien.pendaftaran-jkn');

})->name('pendaftaran.jkn');

/*
|--------------------------------------------------------------------------
| FORM UMUM / NON JKN (LOGIN BARU DIMINTA)
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran-poliklinik/umum', function () {

    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('redirect_to', 'pendaftaran.umum');
    }

    return view('pasien.pendaftaran-umum');

})->name('pendaftaran.umum');

/*
|--------------------------------------------------------------------------
| PENDAFTARAN ONLINE (ENTRY POINT)
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran-online', function () {
    return view('pasien.pendaftaran-poliklinik');
})->name('pendaftaran.online');

/*
|--------------------------------------------------------------------------
| PILIH JENIS PASIEN (PUBLIK)
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran-poliklinik', function () {
    return view('pasien.pendaftaran-poliklinik');
})->name('pendaftaran.poliklinik');

/*
|--------------------------------------------------------------------------
| FORM PASIEN JKN (TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran-poliklinik/jkn', function () {
    return view('pasien.pendaftaran-jkn');
})->name('pendaftaran.jkn');

/*
|--------------------------------------------------------------------------
| FORM PASIEN UMUM & NON JKN (TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran-poliklinik/umum', function () {
    return view('pasien.pendaftaran-umum');
})->name('pendaftaran.umum');


// SUBMIT FORM
Route::post('/pendaftaran-poliklinik/jkn', 
    [PendaftaranPoliController::class, 'storeJkn']
)->name('pendaftaran.jkn.store');

Route::post('/pendaftaran-poliklinik/umum', 
    [PendaftaranPoliController::class, 'storeUmum']
)->name('pendaftaran.umum.store');

Route::get('/pasien/antrian', [AntrianController::class, 'index'])
    ->name('pasien.antrian');

    
// rekam medis

Route::get('/pasien/rekam-medis', [RekamMedisController::class, 'index'])
    ->name('pasien.rekammedis');

Route::get('/rekam-medis-saya', [RekamMedisController::class, 'form'])
    ->name('rekammedis.form');

Route::post('/rekam-medis-saya', [RekamMedisController::class, 'cek'])
    ->name('rekammedis.cek');
    
Route::get(
    '/pasien/rekam-medis/pdf/{token}',
    [App\Http\Controllers\Pasien\RekamMedisController::class, 'pdf']
)->name('pasien.rekammedis.pdf');


use App\Http\Controllers\Profil\ProfilJadwalDokterController;

Route::get('/profil/jadwal-dokter', 
    [ProfilJadwalDokterController::class, 'index']
)->name('profil.jadwal_dokter');
