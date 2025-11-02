<?php

namespace App\Http\Controllers;

use App\Services\Group\GroupService;
use App\Services\City\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class GroupController extends Controller
{
    protected GroupService $groupService;
    protected CityService $cityService;

    public function __construct(GroupService $groupService, CityService $cityService)
    {
        $this->groupService = $groupService;
        $this->cityService = $cityService;
    }

    /**
     * Grup oluşturma formunu gösterir.
     */
    public function create()
    {
        // Sadece şehir listesini çekeriz (diğer dropdownlar AJAX ile gelir)
        $cities = $this->cityService->getAll();
        return view('groups.create', compact('cities'));
    }

    /**
     * Yeni grubu oluşturur.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'groups_name' => 'required|string|max:255',
        'city_id' => 'required|exists:cities,id',
        'university_id' => 'required|exists:universities,id',
        'faculty_id' => 'required|exists:faculties,id',
        'department_id' => 'required|exists:departments,id',
        'class_models_id' => 'required|exists:class_models,id',
    ]);

    $validated['user_id'] = Auth::id();

    $group = $this->groupService->create($validated);

    // ✅ Mevcut kullanıcıyı öğrenci olarak gruba ekle
    $this->groupService->addStudent($group->id, Auth::id());

    // ✅ Rol ataması
    if (!Auth::user()->hasRole('Admin')) {
        Auth::user()->assignRole('Admin');
    }

    return redirect()->route('groups.show', $group)
        ->with('success', 'Grup başarıyla oluşturuldu!');
}

    /**
     * Grup detay sayfasını gösterir.
     */
    public function show(int $id)
    {
        $group = $this->groupService->getById($id);

        $isMember = $group->students()->where('user_id', Auth::id())->exists();

        if (Auth::id() !== $group->user_id && !$isMember) {
            abort(403, 'Bu gruba erişim yetkiniz yok.');
        }

        return view('groups.show', compact('group'));
    }

    /**
     * Gruba yeni öğrenci ekler.
     */
   public function addStudent(Request $request, int $groupId)
{
    $group = $this->groupService->getById($groupId);

    if (Auth::id() !== $group->user_id) {
        abort(403, 'Sadece grup yöneticisi öğrenci ekleyebilir.');
    }

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', Rules\Password::defaults()],
    ]);

    // 👇 sıralamayı düzelttik
    $this->groupService->addStudent($validated, $groupId);

    return back()->with('success', 'Öğrenci başarıyla eklendi!');
}


    /**
     * Gruba yeni duyuru ekler.
     */
    public function storeAnnouncement(Request $request, int $groupId)
    {
        $group = $this->groupService->getById($groupId);

        if (Auth::id() !== $group->user_id) {
            abort(403, 'Sadece grup yöneticisi bildirim gönderebilir.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $group->announcements()->create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Bildirim başarıyla gönderildi!');
    }
}
