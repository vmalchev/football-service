<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\City;

class CityRepository
{
    /**
     *
     * @param array $city
     * @return \Sportal\FootballApi\Model\City
     */
    public function createObject($cityArr)
    {
        $city = new City();
        $city->setName($cityArr['name']);

        if (isset($cityArr['id'])) {
            $city->setId($cityArr['id']);
        }

        if (isset($cityArr['country_id'])) {
            $city->setCountryId($cityArr['country_id']);
        }

        return $city;
    }
}