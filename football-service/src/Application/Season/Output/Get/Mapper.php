<?php


namespace Sportal\FootballApi\Application\Season\Output\Get;

use Sportal\FootballApi\Application\Tournament;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

class Mapper
{
    /**
     * @var Tournament\Output\Get\Mapper
     */
    private Tournament\Output\Get\Mapper $tournamentMapper;
    

    public function __construct(Tournament\Output\Get\Mapper $tournamentMapper)
    {
        $this->tournamentMapper = $tournamentMapper;

    }

    public function map(?ISeasonEntity $seasonEntity):  ?Dto
    {
        if (is_null($seasonEntity)) {
            return null;
        }

        return new Dto(
            $seasonEntity->getId(),
            $seasonEntity->getName(),
            $this->tournamentMapper->map($seasonEntity->getTournament()),
            $seasonEntity->getStatus()
        );
    }
}