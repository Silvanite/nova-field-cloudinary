<?php

namespace Silvanite\NovaFieldCloudinary\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Illuminate\Support\Facades\Storage;

class CloudinaryImage extends Image
{
    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  string|null  $disk
     * @param  callable|null  $storageCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $disk = 'cloudinary', $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->thumbnail(function () {
            return $this->value ? cloudinary_image($this->value, [
                'width' => 64,
                'fetch_format' => 'auto',
            ], $this->disk) : null;
        })->preview(function () {
            return $this->value ? cloudinary_image($this->value, [
                'width' => 318,
                'fetch_format' => 'auto',
            ], $this->disk) : null;
        })->download(function () {
            $image_address = cloudinary_image($this->value);
            return response()->streamDownload(function () use ($image_address) {
                echo file_get_contents($image_address);
            }, $this->value);
        })->delete(function (Request $request, $model) {
            $path = pathinfo($model->{$this->attribute});
            Storage::disk($this->disk)->delete($path['filename']);
            return $this->columnsThatShouldBeDeleted();
        });
    }
}
