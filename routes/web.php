<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController; // Breeze profil denetleyicisi
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

// Herkesin erişebileceği ana sayfa
Route::get('/', function () {
    return view('welcome');
});

// --- GİRİŞ YAPMIŞ VE E-POSTA DOĞRULAMIŞ KULLANCI ALANI ---
// Tüm uygulama rotalarını bu grubun içine alıyoruz.
Route::middleware(['auth', 'verified'])->group(function () {

    // Rol tabanlı Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Breeze Profil Sayfaları
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {



    // Grup İşlemleri
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');

    // Grup Detay, Öğrenci Ekleme ve Bildirim Gönderme
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');//adım 2
    Route::post('/groups/{group}/students', [GroupController::class, 'addStudent'])->name('groups.addStudent');
    Route::post('/groups/{group}/announcements', [GroupController::class, 'storeAnnouncement'])->name('groups.storeAnnouncement');

    // --- Dependent Dropdown için API Rotaları ---
    // Bu rotalar da güvenlik için 'auth' middleware'i altında kalmalı
    Route::get('/api/universities/{city}', [DependentDropdownController::class, 'universities']);
    Route::get('/api/faculties/{university}', [DependentDropdownController::class, 'faculties']);
    Route::get('/api/departments/{faculty}', [DependentDropdownController::class, 'departments']);
    Route::get('/api/classes/{department}', [DependentDropdownController::class, 'classes']);
});




// Breeze'in kimlik doğrulama rotalarını (login, register, vb.) yükler
require __DIR__.'/auth.php';
