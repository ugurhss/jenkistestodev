<?php

namespace App\Repositories\Group;

use App\Models\Group;

class GroupRepository
{
    protected Group $model;

    public function __construct(Group $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['students', 'announcements'])->get();
    }

    public function find(int $id)
    {
        return $this->model->with(['students', 'announcements'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $group = $this->find($id);
        $group->update($data);
        return $group;
    }

    public function delete(int $id)
    {
        $group = $this->find($id);
        return $group->delete();
    }

    // Grup özel işlemleri
  public function attachStudent(int $groupId, int $userId)
{
    $group = $this->find($groupId);
    if ($group && !$group->students()->where('user_id', $userId)->exists()) {
        $group->students()->attach($userId);
    }
}

    public function getStudents(int $groupId)
    {
        return $this->find($groupId)->students;
    }

    public function getGroupsByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }


}
