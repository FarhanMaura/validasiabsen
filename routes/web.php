<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route untuk semua user yang terautentikasi
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk Tata Usaha (Admin)
Route::middleware(['auth'])->group(function () {
    // Data Siswa - CRUD (hanya TU)
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create')->middleware('role:tu');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store')->middleware('role:tu');
    Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
    Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit')->middleware('role:tu');
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update')->middleware('role:tu');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy')->middleware('role:tu');

    // Import/Export Siswa (hanya TU)
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import')->middleware('role:tu');
    Route::get('/siswa/export/template', [SiswaController::class, 'exportTemplate'])->name('siswa.export.template')->middleware('role:tu');

    // Data Kelas - CRUD (hanya TU)
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index')->middleware('role:tu');
    Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create')->middleware('role:tu');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store')->middleware('role:tu');
    Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('kelas.show')->middleware('role:tu');
    Route::get('/kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('kelas.edit')->middleware('role:tu');
    Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update')->middleware('role:tu');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy')->middleware('role:tu');

    // Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
    
    // Scan Barcode (Hanya Guru)
    Route::get('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan')->middleware('role:guru');

    // Export absensi (hanya TU)
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export')->middleware('role:tu');

    // Pengaturan (hanya TU)
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index')->middleware('role:tu');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update')->middleware('role:tu');

    // User Management (Hanya TU)
    Route::resource('users', UserController::class)->middleware('role:tu');

    // Kartu Siswa
    Route::get('/siswa/{siswa}/card', [SiswaController::class, 'card'])->name('siswa.card');

    // Manual Attendance (Guru & TU)
    Route::get('/absensi/manual', [AbsensiController::class, 'createManual'])->name('absensi.manual');
    Route::post('/absensi/manual', [AbsensiController::class, 'storeManual'])->name('absensi.storeManual');
    Route::delete('/absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
});

// Public Profile (Restricted to Guru)
Route::get('/p/{nisn}', [SiswaController::class, 'publicProfile'])
    ->name('siswa.public')
    ->middleware(['auth', 'role:guru']);

// API Routes for RFID and Barcode
Route::post('/api/absensi/check', [AbsensiController::class, 'checkabsen'])->name('api.absensi.check');
Route::post('/api/absensi/barcode', [AbsensiController::class, 'checkBarcode'])->name('api.absensi.barcode');

// Debug route for RFID testing
Route::get('/debug/rfid', function () {
    return view('debug.rfid');
})->middleware('auth');

require __DIR__.'/auth.php';
