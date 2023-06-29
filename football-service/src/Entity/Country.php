<?php

namespace Sportal\FootballApi\Entity;

class Country
{

    const TABLE_NAME = 'country';

    const ID_FIELD = 'id';

    const NAME_FIELD = 'name';

    const FLAG_FIELD = 'flag';

    const ALIAS_FIELD = 'alias';

    private $id;

    private $name;

    private $flag;

    private $alias;

    public function __construct($id, $name, $flag, $alias)
    {
        $this->id = (int)$id;
        $this->name = $name;
        $this->flag = $flag;
        $this->alias = $alias;
    }

    public static function create(array $data): Country
    {
        return new Country($data[static::ID_FIELD],
            $data[static::NAME_FIELD],
            $data[static::FLAG_FIELD] ?? null,
            $data[static::ALIAS_FIELD] ?? null);
    }

    public static function columns(): array
    {
        return [
            static::ID_FIELD,
            static::NAME_FIELD,
            static::FLAG_FIELD,
            static::ALIAS_FIELD,
        ];
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     *
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }
}

