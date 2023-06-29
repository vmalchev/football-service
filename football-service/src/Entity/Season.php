<?php
namespace Sportal\FootballApi\Entity;

class Season
{

    const TABLE_NAME = 'tournament_season';

    const ID_FIELD = 'id';

    const NAME_FIELD = 'name';

    const TOURNAMENT_FIELD = 'tournament';

    const TOURNAMENT_ID_FIELD = 'tournament_id';

    const ACTIVE_FIELD = 'active';

    private $id;

    private $name;

    /**
     *
     * @var Tournament
     */
    private $tournament;

    private $active;

    public function __construct($id, $name, Tournament $tournament, $active)
    {
        $this->id = (int) $id;
        $this->name = $name;
        $this->tournament = $tournament;
        $this->active = (bool) $active;
    }

    /**
     *
     * @return number
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
     * @return \Sportal\FootballApi\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    public static function create(array $data): Season
    {
        return new Season($data[static::ID_FIELD], $data[static::NAME_FIELD], Tournament::create($data[static::TOURNAMENT_FIELD]), $data[static::ACTIVE_FIELD]);
    }

    public static function columns(): array
    {
        return [
            static::ID_FIELD,
            static::NAME_FIELD,
            static::TOURNAMENT_ID_FIELD,
            static::ACTIVE_FIELD
        ];
    }
}

