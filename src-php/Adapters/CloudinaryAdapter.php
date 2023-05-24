<?php

namespace Silvanite\NovaFieldCloudinary\Adapters;

use Cloudinary\Uploader;
use League\Flysystem\Config;
use CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter as CloudinaryBaseAdapter;

class CloudinaryAdapter extends CloudinaryBaseAdapter
{
    protected $resource_types = ['image', 'raw', 'video'];

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

        $cloudinary_options = $config->get('cloudinary') ?? [];

        $path = pathinfo($path)['filename'];

        // Create the options object
        $options = array_merge([
            'public_id' => $path,
            'resource_type' => 'auto'
        ], $cloudinary_options);

        $resource_metadata = stream_get_meta_data($resource);
        $uploaded_metadata = Uploader::upload($resource_metadata['uri'], $options);

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

    /**
     * Delete a resource by the given public id.
     *
     * @param string $path
     * @return boolean
     */
    public function delete($path)
    {
        return collect($this->resource_types)->filter(function ($resource_type) use ($path) {
            try {
                $result = Uploader::destroy($path, ['resource_type' => $resource_type, 'invalidate' => true]);
                is_array($result) ? $result['result'] == 'ok' : false;
            } catch (\Exception $e) {
                return false;
            }
        })->count() >= 1;
    }

    /**
     * Check if a resource with the provided public id exists
     *
     * @param string $path
     * @return boolean
     */
    public function has($path)
    {
        return collect($this->resource_types)->filter(function ($resource_type) use ($path) {
            try {
                $this->api->resource($path, ['resource_type' => $resource_type]);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        })->count() >= 1;
    }
}
