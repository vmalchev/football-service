<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\Country;

class CountryRepository extends Repository
{

    /**
     *
     * @param string $name
     * @return \Sportal\FootballApi\Model\Country|NULL
     */
    public function findByName($name)
    {
        $countries = $this->findAll();
        $name = strtolower($name);
        foreach ($countries as $country) {
            if (strtolower($country->getName()) == $name ||
                ($country->getAlias() !== null && strtolower($country->getAlias()) == $name)) {
                return $country;
            }
        }
        return null;
    }

    /**
     *
     * @param array $countryArr
     * @return \Sportal\FootballApi\Model\Country
     */
    public function createObject($countryArr)
    {
        $country = new Country();
        $country->setName($countryArr['name']);
        if (isset($countryArr['alias'])) {
            $country->setAlias($countryArr['alias']);
        }
        if (isset($countryArr['id'])) {
            $country->setId($countryArr['id']);
        }
        if (isset($countryArr['code'])) {
            $country->setCode($countryArr['code']);
        }
        return $country;
    }

    /**
     *
     * @param int $id
     * @return \Sportal\FootballApi\Model\Country
     */
    public function find($id)
    {
        return $this->getByPk(Country::class, [
            'id' => $id
        ], [
            $this,
            'createObject'
        ]);
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\Country[]
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     */
    public function findAll($filter = array())
    {
        return $this->getAll(Country::class, $filter, array(
            $this,
            'createObject'
        ));
    }

    public function getModelClass()
    {
        return Country::class;
    }
}