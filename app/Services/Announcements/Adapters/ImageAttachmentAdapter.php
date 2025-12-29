<?php

namespace App\Services\Announcements\Adapters;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageAttachmentAdapter implements AttachmentAdapterInterface
{

    public function supports(UploadedFile $file): bool
    {
        return in_array($file->getClientMimeType(), [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ], true);
    }


    public function upload(UploadedFile $file): array
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('announcements/images', $filename, 'public');

        return [
            'path' => $path,
            'type' => 'image',
        ];
    }
}
