<?php

namespace Silvanite\NovaFieldCloudinary\Adapters;

use Cloudinary\Uploader;
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
        $pathInfo = pathinfo($path);

        $resource_metadata = stream_get_meta_data($resource);
        $uploaded_metadata = Uploader::upload(
            $resource_metadata['uri'],
            [
                'public_id' => $pathInfo['filename'],
                'resource_type' => 'auto',
                'folder' => $pathInfo['dirname'] === '.'
                    ? null
                    : $pathInfo['dirname'],
            ]
        );

        return $uploaded_metadata;
    }

    /**
     * Write a new file.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $resource, Config $config)
    {
        $path = pathinfo($path)['filename'];

        return parent::write($path, $resource, $config);
    }

    /**
     * Get the URL of an image with optional transformation parameters
     *
     * @param  string|array $path
     * @return string
     */
    public function getUrl($path)
    {
        if (is_array($path)) {
            return cloudinary_url($path['public_id'], $path['options']);
        }

        return cloudinary_url($path);
    }
}
