<?php

namespace App\Services\Student;

use App\Repositories\Student\StudentRepository;

class StudentService
{
    protected StudentRepository $repository;

    /**
     * Constructor
     * StudentRepository servise enjekte edilir.
     */
    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Yeni öğrenci oluşturur, role atar ve gruba bağlar.
     */
    public function createAndAttachToGroup(array $studentData, int $groupId)
    {
        // Repository ile öğrenci oluşturulur + gruba bağlanır
        $student = $this->repository->createAndAttach($studentData, $groupId);

        // Role atanır
        $student->assignRole('Student');

        return $student;
    }
}
