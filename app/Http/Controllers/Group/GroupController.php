<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use App\Models\Group;
use App\Services\Group\GroupService;
use App\Services\City\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class GroupController extends Controller
{
    use AuthorizesRequests;
    protected GroupService $groupService;
    protected CityService $cityService;

    public function __construct(GroupService $groupService, CityService $cityService)
    {
        $this->groupService = $groupService;
        $this->cityService = $cityService;


    }

    /**
     * Grup oluşturma formu
     */
    public function grupCreate()
    {
        // Policy kontrolü
        $this->authorize('create', Group::class);

        $cities = $this->cityService->getAll();
        return view('groups.create', compact('cities'));
    }

    /**
     * Yeni grup oluşturur.
     */
    public function grupStore(Request $request)
    {
        // Policy kontrolü
        $this->authorize('create', Group::class);

        $validated = $request->validate([
            'groups_name'     => 'required|string|max:255',
            'city_id'         => 'required|exists:cities,id',
            'university_id'   => 'required|exists:universities,id',
            'faculty_id'      => 'required|exists:faculties,id',
            'department_id'   => 'required|exists:departments,id',
            'class_models_id' => 'required|exists:class_models,id',
        ]);

        $validated['user_id'] = Auth::id();

        $group = $this->groupService->create($validated);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Grup başarıyla oluşturuldu!');
    }

    /**
     * Grup detay sayfasını gösterir.
     */
    public function grupShow(int $id)
    {
        $group = $this->groupService->getById($id);

        // Policy kontrolü
        $this->authorize('view', $group);

        return view('groups.show', compact('group'));
    }

    /**
     * Grup düzenleme formu
     */
    public function grupEdit(int $id)
    {
        $group = $this->groupService->getById($id);

        // Policy kontrolü
        $this->authorize('update', $group);

        $cities = $this->cityService->getAll();
        return view('groups.edit', compact('group', 'cities'));
    }

    /**
     * Grup güncelleme işlemi
     */
    public function grupUpdate(Request $request, int $id)
    {
        $group = $this->groupService->getById($id);

        // Policy kontrolü
        $this->authorize('update', $group);

        $validated = $request->validate([
            'groups_name' => 'required|string|max:255',
        ]);

        $this->groupService->update($id, $validated);

        return back()->with('success', 'Grup bilgileri güncellendi.');
    }

    /**
     * Grup silme işlemi
     */
    public function grupDestroy(int $id)
    {
        $group = $this->groupService->getById($id);

        // Policy kontrolü
        $this->authorize('delete', $group);

        $this->groupService->delete($id);

        return redirect()
            ->route('groups.index')
            ->with('success', 'Grup başarıyla silindi.');
    }
}
