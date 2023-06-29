<?php


namespace Sportal\FootballApi\Application\Match\Input\Update;


use DateTimeImmutable;
use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Match\IMatchEntityFactory;
use Sportal\FootballApi\Domain\Match\MatchCoverage;

class Mapper
{
    private IMatchEntityFactory $matchFactory;

    private Referee\Mapper $refereeMapper;

    private Score\Mapper $scoreMapper;

    /**
     * Mapper constructor.
     * @param IMatchEntityFactory $matchFactory
     * @param Referee\Mapper $refereeMapper
     * @param Score\Mapper $scoreMapper
     */
    public function __construct(IMatchEntityFactory $matchFactory, Referee\Mapper $refereeMapper, Score\Mapper $scoreMapper)
    {
        $this->matchFactory = $matchFactory;
        $this->refereeMapper = $refereeMapper;
        $this->scoreMapper = $scoreMapper;
    }


    public function map(Dto $input): IMatchEntity
    {
        $score = $this->scoreMapper->map($input->getScore());
        $referees = $this->refereeMapper->map($input->getReferees());

        return $this->matchFactory->setId($input->getId())
            ->setKickoffTime(new DateTimeImmutable($input->getKickoffTime()))
            ->setStageId($input->getStageId())
            ->setStatusId($input->getStatusId())
            ->setHomeTeamId($input->getHomeTeamId())
            ->setAwayTeamId($input->getAwayTeamId())
            ->setRoundKey($input->getRoundKey())
            ->setGroupId($input->getGroupId())
            ->setVenueId($input->getVenueId())
            ->setReferees($referees)
            ->setScore($score)
            ->setSpectators($input->getSpectators())
            ->setCoverage(MatchCoverage::forKey($input->getCoverage()))
            ->setPhaseStartedAt($input->getPhaseStartedAt() !== null ? new DateTimeImmutable($input->getPhaseStartedAt()) : null)
            ->setFinishedAt($input->getFinishedAt() !== null ? new DateTimeImmutable($input->getFinishedAt()) : null)
            ->create();

    }

}