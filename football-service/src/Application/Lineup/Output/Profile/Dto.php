<?php


namespace Sportal\FootballApi\Application\Lineup\Output\Profile;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IEventNotificationable;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Lineup")
 */
class Dto implements IDto, JsonSerializable, IEventNotificationable
{
    /**
     * @SWG\Property(property="match_id")
     * @var string
     */
    private string $match_id;

    /**
     * @SWG\Property(property="status", enum=LINEUP_STATUS)
     * @var string
     */
    private string $status;

    /**
     * @SWG\Property(property="home_team")
     * @var TeamDto|null
     */
    private ?TeamDto $home_team;

    /**
     * @SWG\Property(property="away_team")
     * @var TeamDto|null
     */
    private ?TeamDto $away_team;

    /**
     * Dto constructor.
     * @param string $match_id
     * @param string $status
     * @param TeamDto|null $home_team
     * @param TeamDto|null $away_team
     */
    public function __construct(string $match_id, string $status, ?TeamDto $home_team = null, ?TeamDto $away_team = null)
    {
        $this->match_id = $match_id;
        $this->status = $status;
        $this->home_team = $home_team;
        $this->away_team = $away_team;
    }

    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->match_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return TeamDto|null
     */
    public function getHomeTeam(): ?TeamDto
    {
        return $this->home_team;
    }

    /**
     * @return TeamDto|null
     */
    public function getAwayTeam(): ?TeamDto
    {
        return $this->away_team;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getId(): string
    {
        return $this->getMatchId();
    }
}