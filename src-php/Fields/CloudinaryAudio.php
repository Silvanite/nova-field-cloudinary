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
            $name = $request->featured_voice_over->getClientOriginalName();
            $ext = '.' . $request->featured_voice_over->getClientOriginalExtension();

            return sha1($name . time()) . $ext;
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
