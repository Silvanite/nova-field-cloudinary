# Cloudinary Image Field and Adapter for Laravel Nova

A Laravel Nova Image Field with Flysystem Adapter for storing and retrieving media from Cloudinary.

This package will enable you to use the [Cloudinary](https://cloudinary.com) service to handle your Nova Image uploads.

## Installation

Install the package using composer

```sh
composer require silvanite/nova-field-cloudinary
```

Add the cloudinary disk to the filesystem config

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

Set the environment variables for your Cloudinary account.

## Usage

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

To use images in your application you can either use the `cloudinary_url()` helper or read the image using the `Storage` facade.

```php
// Using the Storage Facade

return Storage::disk('cloudinary')->url($this->profile_photo, [
    "width" => 200,
    "height" => 200,
    "crop" => "fill",
    "gravity" => "auto",
]);

// Or using the helper

return cloudinary_url($this->profile_photo, [
    "width" => 200,
    "height" => 200,
    "crop" => "fill",
    "gravity" => "auto",
])
```
