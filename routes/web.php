<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenFeatureController;
use App\Http\Controllers\JadwalDosenController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\JadwalMahasiswaController;
use App\Http\Controllers\JamController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\PengambilanMKController;
use App\Http\Controllers\PengampuController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\UserController;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('dashboard-mahasiswa', [MahasiswaController::class, 'studentDashboard'])->name('mahasiswa.dashboard');
    Route::get('mahasiswa/profil', [MahasiswaController::class, 'profil'])->name('mahasiswa.profil');
    Route::get('mahasiswa/profil/edit', [MahasiswaController::class, 'editProfil'])->name('mahasiswa.profil.edit');
    Route::put('mahasiswa/profil', [MahasiswaController::class, 'updateProfil'])->name('mahasiswa.profil.update');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');
    // Route untuk manajemen user (CRUD) oleh admin
    Route::resource('users', UserController::class);
    Route::get('admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.edit-profile');
    Route::put('admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.update-profile');
    Route::put('admin/password/update', [AdminController::class, 'updatePassword'])->name('admin.update-password');
    Route::get('admin/krs-mahasiswa', [AdminController::class, 'krsMahasiswa'])->name('admin.krs.mahasiswa');

    // Routes for PengambilanMK validation
    Route::get('/admin/pengambilanmk/validation', [PengambilanMKController::class, 'adminValidationIndex'])->name('admin.pengambilanmk.validation.index');
    Route::post('/admin/pengambilanmk/validation/{id}/update-status', [PengambilanMKController::class, 'updateStatus'])->name('admin.pengambilanmk.validation.updateStatus');

    Route::get('/admin/mahasiswa/{mahasiswa}/krs', [PengambilanMKController::class, 'showStudentKRS'])->name('admin.mahasiswa.krs.show');
    Route::post('/admin/mahasiswa/{mahasiswa}/krs/approve-all', [PengambilanMKController::class, 'approveAllPending'])->name('admin.pengambilanmk.approveAll');
    // Moved Route::resource('mahasiswa', MahasiswaController::class); here
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('/pengambilan-mk', [PengambilanMKController::class, 'indexForAdmin'])->name('admin.pengambilan-mk.index');
    Route::get('/absensi', [AdminController::class, 'absensi'])->name('admin.absensi.index');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('dashboard-dosen', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
    Route::get('dosen/profil', [DosenController::class, 'profil'])->name('dosen.profil');
    Route::get('dosen/profil/edit', [DosenController::class, 'editProfile'])->name('dosen.edit-profile');
    Route::put('dosen/profil', [DosenController::class, 'updateProfile'])->name('dosen.update-profile');

    // New routes for DosenFeatureController
    Route::get('dosen/mahasiswa', [DosenFeatureController::class, 'daftarMahasiswa'])->name('dosen.mahasiswa.index');
    Route::get('dosen/input-nilai', [DosenFeatureController::class, 'inputNilai'])->name('dosen.nilai.input');
    Route::get('dosen/materi-kuliah', [DosenFeatureController::class, 'materiKuliah'])->name('dosen.materi.index');
    Route::get('pengumuman/create/{jadwalKuliah}', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('dosen/pengambilan-mk', [DosenFeatureController::class, 'pengambilanMk'])->name('dosen.pengambilan-mk.index');
    Route::get('dosen/absensi', [DosenFeatureController::class, 'absensi'])->name('dosen.absensi.index');
});

//     // Route untuk pengambilan MK oleh mahasiswa
Route::get('krs', [PengambilanMKController::class, 'indexForStudent'])->name('krs.index');
Route::get('pengambilan-mk', [PengambilanMKController::class, 'createForStudent'])->name('pengambilan-mk.create');
Route::post('pengambilan-mk', [PengambilanMKController::class, 'storeForStudent'])->name('pengambilan-mk.store');
Route::delete('pengambilan-mk/{matakuliah_id}', [PengambilanMKController::class, 'destroyForStudent'])->name('pengambilan-mk.destroy');
Route::get('krs/export-pdf', [PengambilanMKController::class, 'exportKRS_PDF'])->name('krs.export-pdf');
// });

// Contoh route utama (landing page)

// Resource routes
Route::resource('matakuliah', MatakuliahController::class);
Route::get('/get-next-course-code/{prodi_id}', [MatakuliahController::class, 'getNextCourseCode'])->name('matakuliah.getNextCode');
Route::resource('dosen', DosenController::class);
Route::resource('prodi', ProdiController::class);
Route::resource('ruang', RuangController::class);
Route::resource('kelas', KelasController::class)->parameters([
    'kelas' => 'kelas',
]);
Route::get('/get-kelas-by-prodi/{prodi_id}', [KelasController::class, 'getKelasByProdi'])->name('get.kelas.by.prodi');
Route::resource('jam', JamController::class)->parameters([
    'jam' => 'jam',
]);

Route::get('/pengampu/dosen', [PengampuController::class, 'testDosenRoute']);
Route::get('/get-matakuliah-dosen/{prodi_id}', [PengampuController::class, 'getMatakuliahDosen'])->name('get.matakuliah.dosen');
Route::resource('pengampu', PengampuController::class)->except(['show']);

// Route for pengampu dashboard
Route::get('/pengampu/dashboard', [PengampuController::class, 'index'])->name('pengampu.dashboard');

// Redirect for incorrect /pengampu/matakuliah URL to the correct matakuliah index
Route::redirect('/pengampu/matakuliah', '/matakuliah', 301);

Route::get('/jadwaldosen', [JadwalDosenController::class, 'index'])->name('jadwaldosen.index');
Route::post('/jadwaldosen/copy', [JadwalDosenController::class, 'copyToJadwalDosen'])->name('jadwaldosen.copy');
Route::get('/jadwaldosen/export-pdf', [JadwalDosenController::class, 'exportPDF'])->name('jadwaldosen.exportPDF');

Route::get('/jadwalmahasiswa', [JadwalMahasiswaController::class, 'index'])->name('jadwalmahasiswa.index');
Route::get('/jadwalmahasiswa/export-pdf', [JadwalMahasiswaController::class, 'exportPDF'])->name('jadwalmahasiswa.exportPDF');

Route::resource('jadwal', JadwalKuliahController::class);
Route::post('jadwal/generate', [JadwalKuliahController::class, 'generateJadwal'])->name('jadwal.generate');

// Tambahan untuk navigasi
Route::get('/kelas/navigation', [KelasController::class, 'navigation'])->name('kelas.navigation');
Route::get('/prodi/navigation', [ProdiController::class, 'navigation'])->name('prodi.navigation');
Route::get('/dosen/navigation', [DosenController::class, 'navigation'])->name('dosen.navigation');
Route::get('/ruang/navigation', [RuangController::class, 'navigation'])->name('ruang.navigation');
Route::get('/jam/navigation', [JamController::class, 'navigation'])->name('jam.navigation');
Route::get('/pengampu/navigation', [PengampuController::class, 'navigation'])->name('pengampu.navigation');

Route::view('/', 'welcome');

Route::view('/login', 'auth.login')->name('login.form');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/user/{id}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');

// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::view('/unauthorized', 'unauthorized')->name('unauthorized');

use App\Http\Controllers\PusherController;
use App\Events\TestAblyEvent;

Route::get('/pusher', [PusherController::class, 'index'])->name('pusher.test');
Route::post('/pusher/send', [PusherController::class, 'sendMessage'])->name('pusher.send');

Route::get('/test-ably', function () {
    TestAblyEvent::dispatch('Hello from Ably!');
    return 'Ably event dispatched!';
});
