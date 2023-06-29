<?php
namespace Sportal\FootballApi\Entity;

class Stage
{
    const TABLE_NAME = 'tournament_season_stage';
    
    const TEAM_RELATION_TABLE = 'tournament_season_stage_team';
    
    const TEAM_RELATION_ID_FIELD = 'team_id';
    
    const TEAM_RELATION_REFERENCE = 'tournament_season_stage_id';

    const ID_FIELD = 'id';

    const NAME_FIELD = 'name';

    const SEASON_FIELD = 'tournament_season';

    const START_DATE_FIELD = 'start_date';

    const END_DATE_FIELD = 'end_date';

    const CUP_FIELD = 'cup';

    const LIVE_FIELD = 'live';

    const STAGE_GROUPS_FIELD = 'stage_groups';

    const CONFEDERATION_FIELD = 'confederation';

    private $id;

    private $name;

    /**
     *
     * @var Season
     */
    private $season;

    /**
     *
     * @var \DateTime
     */
    private $startDate;

    /**
     *
     * @var \DateTime
     */
    private $endDate;

    private $cup;

    private $live;

    private $stageGroups;

    private $confederation;

    public function __construct($id, $name, Season $season, \DateTime $startDate = null, \DateTime $endDate = null, $cup, $live, $stageGroups, $confederation)
    {
        $this->id = (int) $id;
        $this->name = $name;
        $this->season = $season;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->cup = $cup;
        $this->live = $live;
        $this->stageGroups = $stageGroups;
        $this->confederation = $confederation;
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
     * @return \Sportal\FootballApi\Entity\Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     *
     * @return boolean
     */
    public function isCup()
    {
        return $this->cup;
    }

    /**
     *
     * @return boolean
     */
    public function isLive()
    {
        return $this->live;
    }

    /**
     *
     * @return mixed
     */
    public function getStageGroups()
    {
        return $this->stageGroups;
    }

    /**
     *
     * @return mixed
     */
    public function getConfederation()
    {
        return $this->confederation;
    }

    public static function create(array $data): Stage
    {
        return new Stage($data[Stage::ID_FIELD],
            $data[static::NAME_FIELD],
            Season::create($data[static::SEASON_FIELD]),
            isset($data[static::START_DATE_FIELD]) ? new \DateTime($data[static::START_DATE_FIELD]) : null,
            isset($data[static::END_DATE_FIELD]) ? new \DateTime($data[static::END_DATE_FIELD]) : null,
            $data[static::CUP_FIELD],
            $data[static::LIVE_FIELD] ?? null,
            $data[static::STAGE_GROUPS_FIELD] ?? null,
            $data[static::CONFEDERATION_FIELD] ?? null);
    }
}

