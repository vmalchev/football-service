<?php
namespace Sportal\FootballApi\Dto\Stage;

use Sportal\FootballApi\Dto\Season\SeasonDto;
use Sportal\FootballApi\Dto\Tournament\TournamentDto;
use Sportal\FootballApi\Entity\Stage;
use Sportal\FootballApi\Dto\Util;
use Sportal\FootballApi\Model\MlContainerInterface;
use Sportal\FootballApi\Model\ContainsMlContent;
use Sportal\FootballApi\Model\Translateable;

/**
 * @SWG\Definition(required={"id", "name", "cup", "tournament", "season"})
 */
class StageDto implements \JsonSerializable, MlContainerInterface, Translateable
{

    use ContainsMlContent;

    /**
     *
     * @SWG\Property()
     * @var int
     */
    private $id;

    /**
     * @SWG\Property()
     * @var string
     */
    private $name;

    /**
     * @SWG\Property()
     * @var boolean
     */
    private $cup;

    /**
     * @SWG\Property()
     * @var SeasonDto
     */
    private $season;

    /**
     * @SWG\Property()
     * @var TournamentDto
     */
    private $tournament;

    /**
     * @SWG\Property()
     * @var string
     */
    private $start_date;

    /**
     * @SWG\Property()
     * @var string
     */
    private $end_date;

    /**
     * @SWG\Property()
     * @var boolean
     */
    private $live;

    /**
     * @SWG\Property()
     * @var int
     */
    private $stage_groups;

    public function __construct($id, $name, $cup, SeasonDto $season, TournamentDto $tournament, \DateTime $start_date = null, \DateTime $end_date = null, $live,
        $stage_groups)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cup = $cup;
        $this->season = $season;
        $this->tournament = $tournament;
        $this->start_date = ($start_date !== null) ? $start_date->format('Y-m-d') : null;
        $this->end_date = ($end_date !== null) ? $end_date->format('Y-m-d') : null;
        $this->live = $live;
        $this->stage_groups = $stage_groups;
    }

    public static function create(Stage $stage, SeasonDto $seasonDto, TournamentDto $tournamentDto)
    {
        return new StageDto($stage->getId(), $stage->getName(), $stage->isCup(), $seasonDto, $tournamentDto, $stage->getStartDate(), $stage->getEndDate(),
            $stage->isLive(), $stage->getStageGroups());
    }

    public function jsonSerialize()
    {
        return Util::filterNull($this->translateContent(get_object_vars($this)));
    }

    public function getContainerName()
    {
        return Stage::TABLE_NAME;
    }

    public function getMlContentModels()
    {
        return array_merge([
            $this
        ], $this->tournament->getMlContentModels());
    }

    public function getId()
    {
        return $this->id;
    }
}

