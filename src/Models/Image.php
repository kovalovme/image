<?php

namespace Kovalovme\Image\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kovalovme\Image\Casts\PathsCast;
use Kovalovme\Image\Database\Factories\ImageFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'disk',
        'paths'
    ];

    protected $casts = [
        'paths' => PathsCast::class
    ];

    /**
     * @return ImageFactory
     */
    protected static function newFactory(): ImageFactory
    {
        return ImageFactory::new();
    }
}
