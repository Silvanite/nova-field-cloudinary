<?php

namespace Silvanite\NovaFieldCloudinary\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;

class CloudinaryFile extends File
{

    use CloudinaryOptions;

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

        $this->store(function(Request $request, $model, $attribute, $requestAttribute){

            $filename = $request->file($requestAttribute)->store($this->getStorageDir(), [
                'disk' => $this->getStorageDisk(),
                'cloudinary' => $this->cloudinaryOptions
            ]);

            // If a folder is specified we ensure a trailing slash
            // If no folder is specified we ensure no beginning slash
            $path = array_key_exists('folder',$this->cloudinaryOptions) ? rtrim($this->cloudinaryOptions['folder'], '/') . '/' : '';

            return $path . $filename;

        })->storeAs(function (Request $request) {
            $name = $request->{$this->attribute}->getClientOriginalName();
            $ext = '.' . $request->{$this->attribute}->getClientOriginalExtension();

            return sha1($name . time()) . $ext;
        })->delete(function (Request $request, $model) {
            $path = pathinfo($model->{$this->attribute});
            Storage::disk($this->disk)->delete($path['dirname'] .'/'. $path['filename']);
            return $this->columnsThatShouldBeDeleted();
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
