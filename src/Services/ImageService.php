<?php

namespace Kovalovme\Image\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kovalovme\Image\Contracts\StorageService;

class ImageService
{

    protected array $preparedData;

    protected StorageService $storageService;

    /**
     * ImageService constructor.
     * @param StorageService $storageService
     */
    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Add image source
     *
     * @param mixed $source
     * @param null $title
     * @return $this
     */
    public function add(mixed $source, $title = null): ImageService
    {
        array_push($this->preparedData, [
            'title'  => $title,
            'source' => $source
        ]);

        return $this;
    }

    /**
     * @param string $disk
     * @return $this
     */
    public function setDisk(string $disk): ImageService
    {
        $this->storageService->setDisk($disk);

        return $this;
    }

    /**
     * Store image to database and to file storage
     *
     * @return Collection
     */
    public function store(): Collection
    {
        $result = new Collection();
        $disk = $this->storageService->getDisk();

        foreach ($this->preparedData as $data) {
            $paths = $this->storageService->store($data['source']);

            $image = Image::create([
                'title' => isset($data['title']) ? $data['title'] : null,
                'disk'  => $disk,
                'paths' => $paths
            ]);

            $result = $result->push($image);
        }

        $this->preparedData = [];

        return $result;
    }

    public function delete(Model $image): void
    {
        foreach ($image->paths as $path) {
            Storage::disk($image->disk)->delete($path);
        }
    }
}
