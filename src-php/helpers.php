<?php

if (!function_exists('cloudinary_image')) {
    /**
     * Get an optimised public url for an image by it's public id
     *
     * @param string $path
     * @param array $options
     * @param string $disk
     * @return string
     */
    function cloudinary_image(string $path, array $options = [], string $disk = 'cloudinary')
    {
        return \Illuminate\Support\Facades\Storage::disk($disk)->url([
            'public_id' => $path,
            'options' => $options,
        ]);
    }
}
