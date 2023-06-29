<?php


namespace Sportal\FootballApi\Repository;


use Sportal\FootballApi\Model\President;

class PresidentRepository
{
    public function getModelClass()
    {
        return President::class;
    }

    public function createObject(array $presidentArray): President
    {
        $president = new President();
        $president->setName($presidentArray['name']);

        if (isset($presidentArray['id'])) {
            $president->setId($presidentArray['id']);
        }

        return $president;
    }
}