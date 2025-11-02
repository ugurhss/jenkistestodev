<?php

namespace App\Repositories\Announcements;

use App\Models\GroupAnnouncement;
use App\Repositories\BaseRepository;

class AnnouncementsRepository extends BaseRepository
{
    public function __construct(GroupAnnouncement $model)
    {
        parent::__construct($model);
    }


}
