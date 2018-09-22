<?php

namespace Silvanite\NovaFieldCloudinary\Fields;

use Laravel\Nova\Fields\Image;

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
            return $this->value ? Storage::disk($this->disk)->url($this->value, [
                'width' => 32,
                'height' => 32,
                "crop" => "fill",
                "gravity" => "auto",
            ]) : null;
        })->preview(function () {
            return $this->value ? Storage::disk($this->disk)->url($this->value, [
                'width' => 318,
                'height' => 212,
                "crop" => "fill",
                "gravity" => "auto",
            ]) : null;
        });
    }
}
