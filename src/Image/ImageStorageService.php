<?php

namespace Image;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as InterventionImage;

class ImageStorageService
{
    protected string $disk;
    protected array $presets;
    protected array $defaults;

    public function __construct()
    {
        $this->disk = config('image.storage.disk');
        $this->presets = config('image.storage.presets');
        $this->defaults = config('image.storage.defaults');
    }

    /**
     * @param $source
     * @return array
     */
    public function store($source)
    {
        $this->preparePresets();
        $paths = [];

        foreach ($this->presets as $preset => $params) {

            // Formatting image and fitting to preset dimensions
            $image = InterventionImage::make($source);
            if (isset($params['fit'])) {
                $image = $image->fit(...$params['fit']);
            }
            $image->encode($params['encoding'], $params['quality'])->__toString();

            // Getting hash fingerprint for image
            $hash = md5($image . Carbon::now()->toDateTimeString());

            // Composing path
            $path = "{$params['folder']}/{$hash}.{$params['encoding']}";

            // Collect path
            $paths = array_merge($paths, ["_{$preset}" => $path]);

            // Store image
            Storage::disk($this->disk)->put($path, $image);
        }

        return $paths;
    }

    /**
     * Prepare Presets
     */
    protected function preparePresets()
    {
        foreach ($this->presets as $preset => $params) {
            $this->presets[$preset] = array_merge($this->defaults, $params);
        }
    }

    public function getdisk()
    {
        return $this->disk;
    }

    public static function disk()
    {
        return (new static)->getdisk();
    }
}
