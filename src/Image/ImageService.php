<?php

namespace Image;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Store image to database and to file storage
     *
     * @param $source
     * @param null $title
     * @return Model|null
     */
    public function store($source, $title = null): ?Model
    {
        $image_service = new ImageStorageService;
        $disk          = $image_service->getdisk();
        $paths         = $image_service->store($source);

        return Image::create([
            'title' => $title,
            'disk'  => $disk,
            'paths' => $paths,
        ]);
    }

    public function delete(Model $image): void
    {
        foreach ($image->paths as $path) {
            Storage::disk($image->disk)->delete($path);
        }
    }
}
