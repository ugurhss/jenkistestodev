<?php

namespace App\Services\Group;

use App\Repositories\Group\GroupRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class GroupService
{
    protected GroupRepository $repository;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        // burası ileride domain mantığı eklemek için ideal yer
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

 public function addStudent(array $studentData, int $groupId)
{
    $student = User::create([
        'name' => $studentData['name'],
        'email' => $studentData['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($studentData['password']),
    ]);

    $student->assignRole('Student');

    $this->repository->attachStudent($groupId, $student->id);

    return $student;
}


      public function createAndAttachStudent(array $studentData, int $groupId)
    {
        $student = User::create([
            'name' => $studentData['name'],
            'email' => $studentData['email'],
            'password' => Hash::make($studentData['password']),
        ]);

        $student->assignRole('Student');

        $this->repository->attachStudent($groupId, $student->id);

        return $student;
    }

    public function getStudents(int $groupId)
    {
        return $this->repository->getStudents($groupId);
    }

    public function getGroupsByUser(int $userId)
    {
        return $this->repository->getGroupsByUserId($userId);
    }
}
