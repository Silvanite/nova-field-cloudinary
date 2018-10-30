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
                'width' => 318,
            ], $this->disk) : null;
        })->preview(function () {
            return $this->value ? cloudinary_image($this->value, [
                'width' => 318,
            ], $this->disk) : null;
        })->delete(function (Request $request, $model) {
            $path = pathinfo($model->{$this->attribute});
            return Storage::disk($this->disk)->delete($path['filename']);
        });
    }
}
