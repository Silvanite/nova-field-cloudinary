# Cloudinary Fields and Adapter for Laravel Nova

A collection of Laravel Nova fields with a Flysystem Adapter for storing and retrieving media from Cloudinary.

This package will enable you to use the [Cloudinary](https://cloudinary.com) service to handle your Nova file uploads.

## Installation

Install the package using composer

```sh
composer require silvanite/nova-field-cloudinary
```

Add the cloudinary disk to the filesystem config and set the environment variables for your Cloudinary account.

```php
// config/filesystem.php
return [
    ...
    'disks' => [
        ...
        'cloudinary' => [
            'driver' => 'cloudinary',
            'api_key' => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        ]
    ]
];
```

## Using the CloudinaryImage Field

Simply use the `CloudinaryImage` field in your Resource's fields instead of the standard Nova `Image` field. This component extends the default Image field so you can use it with all the same options as the standard field.

```php
use Silvanite\NovaFieldCloudinary\Fields\CloudinaryImage;

public function fields(Request $request)
{
    return [
        ...
        CloudinaryImage::make('Profile Photo'),
    ]
}
```

This will essentially do the same as `Image::make()->disk('cloudinary')` but it will also serve resized and optimised preview and thumbnail images within the Nova UI itself. However if you don't want this you can just use the standard `Image` field.

To use images in your application you can either use the `cloudinary_image` helper or read the image using the `Storage` facade.

```php
// Using the helper (with transformation)

return cloudinary_image($this->profile_photo, [
    "width" => 200,
    "height" => 200,
    "crop" => "fill",
    "gravity" => "auto",
])

// Using the Storage Facade (without transformation)

return Storage::disk('cloudinary')->url($this->profile_photo);

// Using the Storage Facade (with transformation)

return Storage::disk('cloudinary')->url([
    'public_id' => $this->profile_photo,
    'options' => [
        "width" => 200,
        "height" => 200,
        "crop" => "fill",
        "gravity" => "auto",
    ],
])
```

### Max file size

Cloudinary imposes maximum file sizes on images depending on your account plan.  At the time of writing the free plan allows images up to 10mb.

If a successfully uploaded image is transformed by cloudinary and upscaled past this file size, the download of that image will fail with a 400 error on the front end.

This situation is especially likely to occur if working with animated GIF images which are typically quite large files at smaller resolutions, liable to upscaling by image processors.

## Using the CloudinaryAudio Field

Simply use the `CloudinaryAudio` field in your Resource's fields. This component extends `davidpiesse/nova-audio` which in turn extends the default Nova File field so you can use it with all the same options as the standard field.

```php
use Silvanite\NovaFieldCloudinary\Fields\CloudinaryAudio;

public function fields(Request $request)
{
    return [
        ...
        CloudinaryAudio::make('Audio Source'),
    ]
}
```

This field sets the disk in use to `Cloudinary` and ensures the media is stored in the database field with the correct file extension.  Further to this, the field sets the preview URL to use the appropriate path within Cloudinary so that the audio can be played back in the CMS.

To use the audio files in your application you can either use the `cloudinary_audio()` helper or read the audio file using the `Storage` facade.  Note, Cloudinary stores both images and video together so if using the `Storage` facade, the `resource_type` should be set to `video` in the options array.

```php
// Using the audio helper

return cloudinary_audio($this->audio_source);

// Using the Storage Facade

return Storage::disk('cloudinary')->url([
    'public_id' => $this->audio_source,
    'options' => [
        "resource_type" => "video",
    ],
])
```

## Using the CloudinaryFile Field

Simply use the `CloudinaryFile` field in your Resource's fields instead of the standard Nova `File` field. This component extends the default File field so you can use it with all the same options as the standard field.

```php
use Silvanite\NovaFieldCloudinary\Fields\CloudinaryFile;

public function fields(Request $request)
{
    return [
        ...
        CloudinaryFile::make('Document'),
    ]
}
```

This field sets the disk in use to `Cloudinary` and ensures the media is stored in the database field with the correct file extension.