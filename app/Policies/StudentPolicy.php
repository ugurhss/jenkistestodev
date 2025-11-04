<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class StudentPolicy
{
    public function addToGroupStudent(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin')) return $group->user_id === $user->id;
        return false;
    }

    public function viewGroupStudents(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin')) return $group->user_id === $user->id;
        return $group->students()->where('user_id', $user->id)->exists();
    }

    public function removeFromGroupStudent(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin')) return $group->user_id === $user->id;
        return false;
    }
}
