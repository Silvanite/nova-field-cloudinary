<?php

namespace Silvanite\NovaFieldCloudinary\Fields;

use Illuminate\Http\Request;
use Davidpiesse\Audio\Audio;
use Illuminate\Support\Facades\Storage;

class CloudinaryAudio extends Audio
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
            return $request->{$this->attribute}->getClientOriginalName();
        });
    }

    public function preview(callable $previewUrlCallback)
    {
        $this->previewUrlCallback = function(){
            return $this->value
                ? cloudinary_audio($this->value, [], $this->disk)
                : null;
        };

        return $this;
    }
}
