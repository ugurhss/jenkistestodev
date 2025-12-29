<?php

namespace App\Services\Announcements;

use App\Models\GroupAnnouncement;
use App\Services\Announcements\Adapters\AttachmentAdapterInterface;
use Illuminate\Http\UploadedFile;

class AnnouncementAttachmentManager
{
    /**
     * @param AttachmentAdapterInterface[] $adapters
     */
    public function __construct(
        protected readonly iterable $adapters,
    ) {
    }

    /**
     * Dosyaları ilgili duyuruya bağlayıp saklar.
     *
     * @param UploadedFile[] $files
     */
    public function storeAttachments(array $files, GroupAnnouncement $announcement): void
    {
        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $adapter = $this->resolveAdapter($file);

            $result = $adapter->upload($file);

            $announcement->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'path'          => $result['path'],
                'mime_type'     => $file->getClientMimeType(),
                'type'          => $result['type'],
                'size'          => $file->getSize(),
            ]);
        }
    }


    protected function resolveAdapter(UploadedFile $file): AttachmentAdapterInterface
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->supports($file)) {
                return $adapter;
            }
        }

        throw new \RuntimeException('Bu dosya tipi için uygun adaptör bulunamadı.');
    }
}
