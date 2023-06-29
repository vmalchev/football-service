<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\Coach;
use Sportal\FootballApi\Model\PartialPerson;

class CoachRepository extends PersonRepository
{

    public function createPartialObject(array $data)
    {
        $object = (new PartialPerson('coach'))->setId($data['id'])->setName($data['name']);
        return $object;
    }

    public function getModelClass()
    {
        return Coach::class;
    }
}