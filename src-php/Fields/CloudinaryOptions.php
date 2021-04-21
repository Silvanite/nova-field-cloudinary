<?php

namespace Silvanite\NovaFieldCloudinary\Fields;

trait CloudinaryOptions
{

    protected $cloudinaryOptions = [];

    public function cloudinary($options = []){
        $this->cloudinaryOptions = $options;

        return $this;
    }

}
