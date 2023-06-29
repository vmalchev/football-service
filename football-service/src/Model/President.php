<?php


namespace Sportal\FootballApi\Model;

use JsonSerializable;

class President implements SurrogateKeyInterface, MlContainerInterface, JsonSerializable, Translateable
{
    use ContainsMlContent;

    private ?int $id = null;

    private string $name;

    public function getPersistanceMap()
    {
        return [
            'name' => $this->name,
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
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        return $this->translateContent($data);
    }
}