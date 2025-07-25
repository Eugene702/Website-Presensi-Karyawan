<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\UserController; // Add this line
use Illuminate\Support\Facades\Auth; // Add this line

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PresensiController::class, 'index'])->name('dashboard');
    Route::post('/clock-in', [PresensiController::class, 'clockIn'])->name('presensi.clockIn');
    Route::get('/clock-out', [PresensiController::class, 'clockOut'])->name('presensi.clockOut');
    Route::get('/presensi/izin', [PresensiController::class, 'izinForm'])->name('presensi.izinForm');
    Route::post('/presensi/izin', [PresensiController::class, 'izinSubmit'])->name('presensi.izinSubmit');
    Route::get('/presensi/riwayat', [PresensiController::class, 'riwayat'])->name('presensi.riwayat');
    Route::get('/presensi/rekap/pdf', [PresensiController::class, 'generateRekapPDF'])->name('presensi.rekap.pdf');

});


Route::post('/upload-profile-picture', [UserController::class, 'uploadProfilePicture'])->name('user.uploadProfilePicture');

Route::view('/', 'home');

// Route::view('dashboard', [PresensiController::class, 'presensi'])
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


require __DIR__.'/auth.php';
