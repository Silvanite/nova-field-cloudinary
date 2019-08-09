<?php

namespace Silvanite\NovaFieldCloudinary\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Illuminate\Support\Facades\Storage;

class CloudinaryFile extends Field
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

        $this->storeAs(function (Request $request) {
            $name = $request->{$this->attribute}->getClientOriginalName();
            $ext = '.' . $request->{$this->attribute}->getClientOriginalExtension();

            return sha1($name . time()) . $ext;
        });
    }

    public function preview(callable $previewUrlCallback)
    {
        $this->previewUrlCallback = function () {
            return $this->value
                ? cloudinary_file($this->value, [], $this->disk)
                : null;
        };

        return $this;
    }
}
