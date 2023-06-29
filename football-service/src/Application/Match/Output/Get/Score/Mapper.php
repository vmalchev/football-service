<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Score;


use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\ITeamScore;

class Mapper
{
    public function map(?IMatchScore $score): ?Dto
    {
        if ($score === null) {
            return null;
        }

        return new Dto(
            $this->createScore($score->getTotal()),
            $this->createScore($score->getHalfTime()),
            $this->createScore($score->getRegularTime()),
            $this->createScore($score->getExtraTime()),
            $this->createScore($score->getPenaltyShootout()),
            $this->createScore($score->getAggregate())
        );
    }

    private function createScore(?ITeamScore $score): ?TeamScoreDto
    {
        if ($score == null) {
            return null;
        }
        return new TeamScoreDto($score->getHome(), $score->getAway());
    }
}