<?php

namespace App\Services\Announcements;

use App\Repositories\Announcements\AnnouncementsRepository;
use App\Services\BaseService;

class AnnouncementService extends BaseService
{

    public function __construct(AnnouncementsRepository $repository)
    {
        parent::__construct($repository);
    }

}
