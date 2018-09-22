<?php

namespace Silvanite\NovaFieldCloudinary\Adapters;

use League\Flysystem\Config;
use CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter as CloudinaryBaseAdapter;

class CloudinaryAdapter extends CloudinaryBaseAdapter
{
    /**
     * Write a new file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
        $path = pathinfo($path)['filename'];

        parent::writeStream($path, $resource, $config);
    }

    /**
     * Get the URL of an image with optional transformation parameters
     *
     * @param  string|array $path
     * @param  array $options
     * @return string
     */
    public function getUrl($path, $options = null)
    {
        if (is_array($path)) {
            return cloudinary_url($path['public_id'], $path['options']);
        }

        if ($options) {
            return cloudinary_url($path, $options);
        }

        return cloudinary_url($path);
    }
}
