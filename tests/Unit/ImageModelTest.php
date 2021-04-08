<?php


namespace Kovalovme\Image\Tests\Unit;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Kovalovme\Image\Models\Image;
use Kovalovme\Image\PathsBag;

class ImageModelTest extends \Kovalovme\Image\Tests\TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_image_has_title()
    {
        $this->assertEquals('test_title', $this->testImageModel->title);
    }

    /** @test */
    function an_image_has_disk()
    {
        $this->assertEquals('test_disk', $this->testImageModel->disk);
    }

    /** @test */
    function an_image_has_paths()
    {
        $this->assertEquals(new PathsBag($this->testImageModel, json_encode([
            '_test1' => 'test1',
            '_test2' => 'test2',
        ])), $this->testImageModel->paths);
    }
}