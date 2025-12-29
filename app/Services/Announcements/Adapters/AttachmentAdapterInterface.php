<?php

namespace App\Services\Announcements\Adapters;

use Illuminate\Http\UploadedFile;

interface AttachmentAdapterInterface
{

    public function supports(UploadedFile $file): bool;


    public function upload(UploadedFile $file): array;
}
