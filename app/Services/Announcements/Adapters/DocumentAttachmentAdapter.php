<?php

namespace App\Services\Announcements\Adapters;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DocumentAttachmentAdapter implements AttachmentAdapterInterface
{
    /**
     * PDF Word ve Excel dosyalarını destekleyen adaptör
     */
    public function supports(UploadedFile $file): bool
    {
        return in_array($file->getClientOriginalExtension(), [
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'csv',
        ], true);
    }

    /**
     * dosyayı doküman klasörüne kaydeder ve bilgilerini döner
     */
    public function upload(UploadedFile $file): array
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('announcements/documents', $filename, 'public');

        return [
            'path' => $path,
            'type' => 'document',
        ];
    }
}
