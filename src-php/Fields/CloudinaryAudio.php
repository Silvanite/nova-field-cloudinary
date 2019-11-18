<?php

namespace Silvanite\NovaFieldCloudinary\Fields;

use Illuminate\Http\Request;
use Davidpiesse\Audio\Audio;

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
            $name = $request->{$this->attribute}->getClientOriginalName();
            $ext = '.' . $request->{$this->attribute}->getClientOriginalExtension();

            return sha1($name . time()) . $ext;
        })->delete(function (Request $request, $model) {
            $path = pathinfo($model->{$this->attribute});
            Storage::disk($this->disk)->delete($path['filename']);
            return $this->columnsThatShouldBeDeleted();
        });
    }

    public function preview(callable $previewUrlCallback)
    {
        $this->previewUrlCallback = function () {
            return $this->value
                ? cloudinary_audio($this->value, [], $this->disk)
                : null;
        };

        return $this;
    }
}
