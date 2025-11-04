<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Grup oluşturabilir mi?
     */
    public function create(User $user): bool
    {
        // Sadece admin veya superadmin oluşturabilir
        return $user->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Grubu görüntüleyebilir mi?
     */
    public function view(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $group->user_id === $user->id;
        }

        // Student kendi veya üyesi olduğu grubu görebilir
        $isMember = $group->students()->where('user_id', $user->id)->exists();
        return $group->user_id === $user->id || $isMember;
    }

    /**
     * Grubu güncelleyebilir mi?
     */
    public function update(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $group->user_id === $user->id;
        }

        return false;
    }

    /**
     * Grubu silebilir mi?
     */
    public function delete(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $group->user_id === $user->id;
        }

        return false;
    }
}
