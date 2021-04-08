<?php


namespace Kovalovme\Image\Contracts;


interface StorageService
{
    /**
     * Set disk
     *
     * @param string $disk
     */
    public function setDisk(string $disk): void;

    /**
     * Get disk
     *
     * @return string
     */
    public function getDisk(): string;

    /**
     * Set sizes
     *
     * @param array $presets
     */
    public function setSizes(array $presets): void;

    /**
     * Store Image
     *
     * @param mixed $source
     * @return array
     */
    public function store(mixed $source): array;
}