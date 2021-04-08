<?php

namespace Kovalovme\Image\Tests;


use Kovalovme\Image\ImageServiceProvider;
use Kovalovme\Image\Models\Image;
use phpDocumentor\Reflection\Types\This;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected Image $testImageModel;

    protected function getPackageProviders($app)
    {
        return [
            ImageServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->setUpConfig();

        $app->bind('path.public', fn() => $this->getTempDirectory());
    }

    protected function setUpConfig()
    {
        config()->set('image.model', \Kovalovme\Image\Models\Image::class);
        config()->set('image.disk', 'public');
        config()->set('image.default_preset', [
            'folder'   => 'general',
            'encoding' => 'jpg',
            'quality'  => 70
        ]);
        config()->set('image.presets', [
            'source'  => [
                'folder' => 'source',
            ],
            '100x75'  => [
                'folder' => '100x75',
                'fit'    => [100, 75],
            ]
        ]);
    }

    protected function setUpDatabase($app)
    {
        include_once __DIR__ . '/../database/migrations/create_images_table.stub';

        (new \CreateImagesTable)->up();

        $app->make('image.model')->create([
            'title' => 'test_title',
            'disk'  => 'test_disk',
            'paths' => [
                '_test1' => 'test1',
                '_test2' => 'test2',
            ]
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->testImageModel = $this->app->make('image.model')->first();
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }

    protected function setUpTempTestFiles()
    {
        $this->initializeDirectory($this->getTestFilesDirectory());
        File::copyDirectory(__DIR__ . '/TestSupport/testfiles', $this->getTestFilesDirectory());
    }

    protected function getTempDirectory($suffix = ''): string
    {
        return __DIR__ . '/TestSupport/temp' . ($suffix == '' ? '' : '/' . $suffix);
    }

    public function getTestFilesDirectory($suffix = ''): string
    {
        return $this->getTempDirectory() . '/testfiles' . ($suffix == '' ? '' : '/' . $suffix);
    }

    public function getTestJpg(): string
    {
        return $this->getTestFilesDirectory('test.jpg');
    }

    public function getTestPng(): string
    {
        return $this->getTestFilesDirectory('test.png');
    }

    public function getTestSvg(): string
    {
        return $this->getTestFilesDirectory('test.svg');
    }
}
