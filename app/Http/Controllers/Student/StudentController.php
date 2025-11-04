<?php

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;

use App\Models\Group;
use App\Models\Student;
use App\Services\Group\GroupService;
use App\Services\Student\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StudentController extends Controller
{
    use AuthorizesRequests;

    protected StudentService $studentService;
    protected GroupService $groupService;

    /**
     * Constructor
     */
    public function __construct(StudentService $studentService, GroupService $groupService)
    {
        $this->studentService = $studentService;
        $this->groupService = $groupService;


    }

    /**
     *  Belirli bir gruba yeni öğrenci ekler.
     */
    public function studentStore(Request $request, int $groupId)
    {
        $group = $this->groupService->getById($groupId);

        // Policy kontrolü (StudentPolicy@addToGroup)
        $this->authorize('addToGroupStudent', $group);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $this->studentService->createAndAttachToGroup($validated, $groupId);

        return back()->with('success', 'Öğrenci başarıyla eklendi!');
    }

    /**
     *  Gruba bağlı öğrencileri listeler.
     */
    public function studentIndex(int $groupId)
    {
        $group = $this->groupService->getById($groupId);

        // Policy kontrolü (StudentPolicy@viewGroupStudents)
        $this->authorize('viewGroupStudents', $group);

        $students = $this->groupService->getStudents($groupId);

        return view('students.index', compact('group', 'students'));
    }

    /**
     *  Öğrenciyi gruptan kaldırır.
     */
    public function studentDestroy(int $groupId, int $studentId)
    {
        $group = $this->groupService->getById($groupId);

        // Policy kontrolü (StudentPolicy@removeFromGroup)
        $this->authorize('removeFromGroupStudent', $group);

        $group->students()->detach($studentId);

        return back()->with('success', 'Öğrenci gruptan Cıkarildi.');
    }
}
