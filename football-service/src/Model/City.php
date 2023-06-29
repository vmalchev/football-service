<?php


namespace Sportal\FootballApi\Model;

use JsonSerializable;

class City implements SurrogateKeyInterface, MlContainerInterface, JsonSerializable, Translateable
{
    use ContainsMlContent;

    private ?int $id = null;

    private string $name;

    private int $country_id;

    public function getPersistanceMap()
    {
        return [
            'name' => $this->name,
            'country_id' => $this->country_id,
        ];
    }

    public function getPrimaryKeyMap()
    {
        return [
            'id' => $this->id
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getCountryId(): int
    {
        return $this->country_id;
    }

    public function setCountryId(int $country_id)
    {
        $this->country_id = $country_id;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        return [
            $this
        ];
    }

    public function jsonSerialize()
    {
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'country_id' => $this->country_id,
        );

        return $this->translateContent($data);
    }

    public function getTranslatedName()
    {
        return $this->mlContent[$this->langCode]['name'] ?? $this->name;
    }
}