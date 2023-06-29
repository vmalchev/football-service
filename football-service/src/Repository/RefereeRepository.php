<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\Referee;
use Sportal\FootballApi\Model\PartialPerson;

class RefereeRepository extends PersonRepository
{
    public function createPartialObject(array $data)
    {
        $object = (new PartialPerson('referee'))->setId($data['id'])->setName($data['name']);
        return $object;
    }
    
    public function getModelClass()
    {
        return Referee::class;
    }
}