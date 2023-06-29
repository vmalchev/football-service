<?php


namespace Sportal\FootballApi\Application\Match\Input\Update\Score;


use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\IMatchScoreFactory;
use Sportal\FootballApi\Domain\Match\ITeamScore;
use Sportal\FootballApi\Domain\Match\ITeamScoreFactory;

class Mapper
{
    private IMatchScoreFactory $scoreFactory;

    private ITeamScoreFactory $teamScoreFactory;

    /**
     * Mapper constructor.
     * @param IMatchScoreFactory $scoreFactory
     * @param ITeamScoreFactory $teamScoreFactory
     */
    public function __construct(IMatchScoreFactory $scoreFactory, ITeamScoreFactory $teamScoreFactory)
    {
        $this->scoreFactory = $scoreFactory;
        $this->teamScoreFactory = $teamScoreFactory;
    }


    public function map(?Dto $dto): ?IMatchScore
    {
        if ($dto === null) {
            return null;
        }

        return $this->scoreFactory->setEmpty()
            ->setTotal($this->createScore($dto->getTotal()))
            ->setHalfTime($this->createScore($dto->getHalfTime()))
            ->setRegularTime($this->createScore($dto->getRegularTime()))
            ->setExtraTime($this->createScore($dto->getExtraTime()))
            ->setPenaltyShootout($this->createScore($dto->getPenaltyShootout()))
            ->setAggregate($this->createScore($dto->getAggregate()))
            ->create();
    }

    private function createScore(?TeamScoreDto $dto): ?ITeamScore
    {
        if ($dto === null) {
            return null;
        }
        return $this->teamScoreFactory->setEmpty()
            ->setHome($dto->getHome())
            ->setAway($dto->getAway())
            ->create();

    }
}