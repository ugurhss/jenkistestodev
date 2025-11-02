<?php

namespace App\Http\Controllers\Dashboard; // <--- 1. DÜZELTME: Namespace güncellendi

use App\Http\Controllers\Controller; // <--- 2. DÜZELTME: Temel Controller sınıfı eklendi
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Kullanıcıyı rolüne göre ilgili dashboard'a yönlendirir.
     */
    public function index()
    {
        $user = Auth::user(); // Giriş yapan kullanıcıyı al

        // Spatie rol kontrolü
        if ($user->hasRole('superadmin')) {
            return $this->superAdminDashboard();
        }

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        if ($user->hasRole('student')) {
            // Öğrenci dashboard'u için user nesnesini metoda gönderiyoruz
            return $this->studentDashboard($user);
        }

        // Hiçbir role uymuyorsa varsayılan
        return $this->defaultDashboard();
    }

    /**
     * Super Admin için dashboard verilerini hazırlar ve view'ı döndürür.
     */
    protected function superAdminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::role('admin')->count(),
            'total_students' => User::role('student')->count(),
            'system_health' => 'Optimal' // Örnek veri
        ];

        return view('dashboard.superadmin.dashboard', compact('stats'));
    }

    /**
     * Admin (Grup Yöneticisi) için dashboard verilerini hazırlar ve view'ı döndürür.
     */
    protected function adminDashboard()
    {
        // Not: Bu 'admin' rolü, bizim sistemimizdeki "grubu oluşturan kişi" (group owner)
        // rolüyle aynıysa, buradaki sorguları ona göre özelleştirebiliriz.
        // Şimdilik sizin verdiğiniz örnekle ilerliyorum.

        // Admin'in kendi yönettiği gruplardaki öğrenci sayısı vb. daha mantıklı olabilir.
        // Örn: $adminGroups = Auth::user()->ownedGroups()->withCount('students')->get();

        $stats = [
            'active_students' => User::role('student')->count(), // Tüm sistemdeki öğrenciler
            'pending_approvals' => 0, // Örnek veri
            'recent_activity' => 0  // Örnek veri
        ];

        return view('dashboard.admin.dashboard', compact('stats'));
    }

    /**
     * Student (Öğrenci) için dashboard verilerini hazırlar ve view'ı döndürür.
     * BURASI ÖNEMLİ: Önceki index() metodumuzun mantığını buraya taşıdık.
     *
     * @param User $user Giriş yapmış olan öğrenci
     * @return \Illuminate\View\View
     */
    protected function studentDashboard(User $user)
    {
        // Öğrencinin üye olduğu grupları, bu grupların duyurularını
        // ve duyuruyu yazan kullanıcıyı (admini) peşin yüklüyoruz (Eager Loading).
        $user->load('groups.announcements.user');

        // View'ı kullanıcının istediği yola yönlendir: 'dashboard.user.dashboard'
        // 'user' değişkenini (tüm bildirimleriyle birlikte) view'a gönderiyoruz.
        return view('dashboard.user.dashboard', compact('user'));
    }

    /**
     * Hiçbir role sahip olmayan veya varsayılan kullanıcılar için.
     */
    protected function defaultDashboard()
    {
        // Orijinal resources/views/dashboard.blade.php
        return view('dashboard');
    }
}
