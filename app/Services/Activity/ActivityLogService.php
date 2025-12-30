<?php

namespace App\Services\Activity;

use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\GroupAnnouncement;
use App\Models\User;

class ActivityLogService
{

    public function logGroupCreated(Group $group, ?User $actor = null): void
    {
        $actorName = $actor?->name ?? 'Sistem';

        $description = sprintf(
            '"%s" adlı kullanıcı "%s" adlı yeni bir grup oluşturdu.',
            $actorName,
            $group->groups_name
        );

        ActivityLog::create([
            'event'        => 'group_created',
            'description'  => $description,
            'actor_id'     => $actor?->id,
            'actor_name'   => $actorName,
            'subject_type' => Group::class,
            'subject_id'   => $group->id,
            'meta'         => [
                'group_id'      => $group->id,
                'group_name'    => $group->groups_name,
                'owner_id'      => $group->user_id,
                'city_id'       => $group->city_id,
                'university_id' => $group->university_id,
            ],
        ]);
    }

    public function logStudentAddedToGroup(int $groupId, User $student, ?User $actor = null): void
    {
        $actorName = $actor?->name ?? 'Sistem';

        $description = sprintf(
            '"%s" numaralı öğrenci (%s) ID\'si %d olan gruba eklendi. İşlemi yapan: %s.',
            $student->no ?? '-',
            $student->name ?? 'İsimsiz',
            $groupId,
            $actorName
        );

        ActivityLog::create([
            'event'        => 'student_added_to_group',
            'description'  => $description,
            'actor_id'     => $actor?->id,
            'actor_name'   => $actorName,
            'subject_type' => User::class,
            'subject_id'   => $student->id,
            'meta'         => [
                'group_id'     => $groupId,
                'student_id'   => $student->id,
                'student_no'   => $student->no,
                'student_name' => $student->name,
            ],
        ]);
    }

    public function logStudentRemovedFromGroup(Group $group, User $student, ?User $actor = null): void
    {
        $actorName = $actor?->name ?? 'Sistem';

        $description = sprintf(
            '"%s" numaralı öğrenci (%s) ID\'si %d olan gruptan çıkarıldı. İşlemi yapan: %s.',
            $student->no ?? '-',
            $student->name ?? 'İsimsiz',
            $group->id,
            $actorName
        );

        ActivityLog::create([
            'event'        => 'student_removed_from_group',
            'description'  => $description,
            'actor_id'     => $actor?->id,
            'actor_name'   => $actorName,
            'subject_type' => Group::class,
            'subject_id'   => $group->id,
            'meta'         => [
                'group_id'     => $group->id,
                'group_name'   => $group->groups_name,
                'student_id'   => $student->id,
                'student_no'   => $student->no,
                'student_name' => $student->name,
            ],
        ]);
    }


    public function logAnnouncementCreated(GroupAnnouncement $announcement, ?User $actor = null): void
    {
        $actorName = $actor?->name ?? 'Sistem';

        $description = sprintf(
            '"%s" adlı kullanıcı, "%s" başlıklı bir duyuru oluşturdu. (Grup ID: %d)',
            $actorName,
            $announcement->title,
            $announcement->group_id
        );

        ActivityLog::create([
            'event'        => 'announcement_created',
            'description'  => $description,
            'actor_id'     => $actor?->id,
            'actor_name'   => $actorName,
            'subject_type' => GroupAnnouncement::class,
            'subject_id'   => $announcement->id,
            'meta'         => [
                'announcement_id' => $announcement->id,
                'group_id'        => $announcement->group_id,
                'title'           => $announcement->title,
            ],
        ]);
    }


    public function logUnauthorizedGroupAccess(User $user, Group $group, string $action): void
    {
        $groupName = $group->groups_name ?? ('#' . $group->id);
        $actorName = $user->name ?? 'Bilinmeyen';

        $description = sprintf(
            'Yetkisiz Grup Erişimi: "%s" adlı grupta "%s" işlemini yapmaya çalıştı fakat yetkisi yok. İşlemi yapan: %s.',
            $groupName,
            $action,
            $actorName
        );

        ActivityLog::create([
            'event'        => 'unauthorized_group_access',
            'description'  => $description,
            'actor_id'     => $user->id,
            'actor_name'   => $actorName,
            'subject_type' => Group::class,
            'subject_id'   => $group->id,
            'meta'         => [
                'action'    => $action,
                'group_id'  => $group->id,
                'group_name'=> $groupName,
                'user_id'   => $user->id,
                'user_name' => $actorName,
            ],
        ]);
    }

    public function logUnauthorizedAnnouncementAccess(User $user, GroupAnnouncement $announcement, string $action): void
    {
        $announcementTitle = $announcement->title ?? ('#' . $announcement->id);
        $actorName = $user->name ?? 'Bilinmeyen';

        $description = sprintf(
            'Yetkisiz Duyuru Erişimi: "%s" duyurusunda "%s" işlemini yapmaya çalıştı fakat yetkisi yok. İşlemi yapan: %s.',
            $announcementTitle,
            $action,
            $actorName
        );

        ActivityLog::create([
            'event'        => 'unauthorized_announcement_access',
            'description'  => $description,
            'actor_id'     => $user->id,
            'actor_name'   => $actorName,
            'subject_type' => GroupAnnouncement::class,
            'subject_id'   => $announcement->id,
            'meta'         => [
                'action'          => $action,
                'announcement_id' => $announcement->id,
                'title'           => $announcementTitle,
                'group_id'        => $announcement->group_id,
                'user_id'         => $user->id,
                'user_name'       => $actorName,
            ],
        ]);
    }
}
