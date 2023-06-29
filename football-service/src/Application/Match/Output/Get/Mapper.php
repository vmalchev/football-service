<?php


namespace Sportal\FootballApi\Application\Match\Output\Get;


use DateTimeInterface;
use Sportal\FootballApi\Application\Group;
use Sportal\FootballApi\Application\KnockoutScheme;
use Sportal\FootballApi\Application\MatchStatus;
use Sportal\FootballApi\Application\Season;
use Sportal\FootballApi\Domain\Match\ColorTeamType;
use Sportal\FootballApi\Domain\Match\IMatchProfile;
use Sportal\FootballApi\Domain\Match\MatchCoverage;

class Mapper
{
    private MatchStatus\Output\Get\Mapper $statusMapper;

    private \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper;

    private Team\Mapper $teamMapper;

    private Group\Output\Get\Mapper $groupMapper;

    private Season\Output\Get\Mapper $seasonMapper;

    private Referee\Mapper $refereeMapper;

    private Venue\Mapper $venueMapper;

    private Score\Mapper $scoreMapper;

    private \Sportal\FootballApi\Application\Round\Output\Partial\Mapper $roundMapper;

    /**
     * Mapper constructor.
     * @param MatchStatus\Output\Get\Mapper $statusMapper
     * @param \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper
     * @param Team\Mapper $teamMapper
     * @param Group\Output\Get\Mapper $groupMapper
     * @param Season\Output\Get\Mapper $seasonMapper
     * @param Referee\Mapper $refereeMapper
     * @param Venue\Mapper $venueMapper
     * @param Score\Mapper $scoreMapper
     * @param \Sportal\FootballApi\Application\Round\Output\Partial\Mapper $roundMapper
     */
    public function __construct(MatchStatus\Output\Get\Mapper                            $statusMapper,
                                \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper,
                                Team\Mapper                                              $teamMapper,
                                Group\Output\Get\Mapper                                  $groupMapper,
                                Season\Output\Get\Mapper                                 $seasonMapper,
                                Referee\Mapper                                           $refereeMapper,
                                Venue\Mapper                                             $venueMapper,
                                Score\Mapper                                             $scoreMapper,
                                \Sportal\FootballApi\Application\Round\Output\Partial\Mapper $roundMapper)
    {
        $this->statusMapper = $statusMapper;
        $this->stageMapper = $stageMapper;
        $this->teamMapper = $teamMapper;
        $this->groupMapper = $groupMapper;
        $this->seasonMapper = $seasonMapper;
        $this->refereeMapper = $refereeMapper;
        $this->venueMapper = $venueMapper;
        $this->scoreMapper = $scoreMapper;
        $this->roundMapper = $roundMapper;
    }


    public function map(IMatchProfile $matchModel): Dto
    {
        $matchEntity = $matchModel->getMatch();
        $minute = $matchModel->getMinute();
        $colors = $matchModel->getTeamColors();
        return new Dto(
            $matchEntity->getId(),
            $this->statusMapper->map($matchEntity->getStatus()),
            $matchEntity->getKickoffTime()->format(DateTimeInterface::ATOM),
            $this->stageMapper->map($matchEntity->getStage()),
            $this->seasonMapper->map($matchEntity->getStage()->getSeason()),
            $this->groupMapper->map($matchEntity->getGroup()),
            $this->roundMapper->map($matchEntity->getRound()),
            $this->teamMapper->map($matchEntity->getHomeTeam(), $colors->getColorByType(ColorTeamType::HOME())),
            $this->teamMapper->map($matchEntity->getAwayTeam(), $colors->getColorByType(ColorTeamType::AWAY())),
            $this->refereeMapper->map($matchEntity->getReferees()),
            $this->venueMapper->map($matchEntity->getVenue()),
            $matchEntity->getSpectators(),
            $matchEntity->getCoverage() !== null ? $matchEntity->getCoverage()->getKey() : MatchCoverage::UNKNOWN()->getKey(),
            $minute !== null ? new Minute\Dto($minute->getRegular(), $minute->getInjury()) : null,
            ($matchEntity->getPhaseStartedAt() !== null) ? $matchEntity->getPhaseStartedAt()->format(DateTimeInterface::ATOM) : null,
            ($matchEntity->getFinishedAt() !== null) ? $matchEntity->getFinishedAt()->format(DateTimeInterface::ATOM) : null,
            $this->scoreMapper->map($matchEntity->getScore())
        );
    }

}