<?php

namespace App\Repositories\Group;

use App\Models\Group;

class GroupRepository
{
    protected Group $model;

    /**
     * Repository constructor.
     * Burada Group modelini enjekte ediyoruz.
     */
    public function __construct(Group $model)
    {
        $this->model = $model;
    }

    /**
     * Tüm grupları getirir.
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * ID’ye göre grup döndürür.
     */
    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Yeni grup oluşturur.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Grubu günceller.
     */
    public function update(int $id, array $data)
    {
        $group = $this->find($id);
        $group->update($data);
        return $group;
    }

    /**
     * Grubu siler.
     */
    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Gruba bağlı öğrencileri getirir.
     */
    public function getStudents(int $groupId)
    {
        $group = $this->find($groupId);
        return $group->students;
    }

    /**
     * Kullanıcının oluşturduğu grupları getirir.
     */
    public function getGroupsByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }
}
