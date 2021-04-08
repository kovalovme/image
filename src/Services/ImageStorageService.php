<?php

namespace Kovalovme\Image\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use Kovalovme\Image\Contracts\StorageService;

class ImageStorageService implements StorageService
{
    const INTERVENTION_METHODS = [
        'fit',
        'ellipse',
        'circle'
    ];

    protected array $sizes = [];

    /**
     * ImageStorageService constructor.
     * @param string $disk
     * @param array $presets
     * @param array $default_preset
     */
    public function __construct(
        protected string $disk,
        protected array $presets,
        protected array $default_preset
    )
    {
    }

    /**
     * Prepare Presets
     */
    protected function preparePresets(): array
    {
        $presets = $this->presets;

        foreach ($presets as $preset => $params) {
            if (!empty($this->sizes)) {
                if (!in_array($preset, $this->sizes)) {
                    unset($presets[$preset]);
                    continue;
                }
            }
            $presets[$preset] = array_merge($this->default_preset, $params);
        }

        return $presets;


    }

    /**
     * @param $source
     * @return array
     */
    public function store(mixed $source): array
    {
        $presets = $this->preparePresets();
        $paths = [];

        foreach ($presets as $preset => $params) {

            // Formatting image and fitting to preset dimensions
            $image = InterventionImage::make($source);

            // Apply configured methods
            $image = $this->applyMethods($image, $params);

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
     * Apply configured methods
     *
     * @param $image
     * @param $params
     * @return InterventionImage
     */
    private function applyMethods($image, $params): InterventionImage
    {
        foreach ($params as $preset => $data) {
            if (in_array($preset, self::INTERVENTION_METHODS)) {
                if (is_array($data)) {
                    $image->{$preset}(...$data);
                } else {
                    $image->{$preset}($data);
                }
            }
        }

        return $image;
    }

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    public function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }

    public function setSizes(array $presets): void
    {
        $this->sizes = $presets;
    }
}
