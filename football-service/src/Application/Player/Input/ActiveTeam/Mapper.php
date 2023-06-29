<?php


namespace Sportal\FootballApi\Application\Player\Input\ActiveTeam;


use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntityFactory;
use Sportal\FootballApi\Domain\TeamSquad\PlayerContractType;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;
use Sportal\FootballApi\Infrastructure\Database\Converter\DateTimeConverter;

class Mapper
{

    /**
     * @var ITeamPlayerEntityFactory
     */
    private ITeamPlayerEntityFactory $teamPlayerEntityFactory;

    /**
     * Mapper constructor.
     * @param ITeamPlayerEntityFactory $teamPlayerEntityFactory
     */
    public function __construct(ITeamPlayerEntityFactory $teamPlayerEntityFactory)
    {
        $this->teamPlayerEntityFactory = $teamPlayerEntityFactory;
    }

    public function map(string $playerId, Dto $dto): ITeamPlayerEntity
    {
        return $this->teamPlayerEntityFactory->setEmpty()
            ->setPlayerId($playerId)
            ->setTeamId($dto->getTeamId())
            ->setContractType(PlayerContractType::forKey($dto->getContractType()))
            ->setStartDate(DateTimeConverter::fromValue($dto->getStartDate()))
            ->setShirtNumber($dto->getShirtNumber())
            ->setStatus(TeamSquadStatus::ACTIVE())
            ->create();
    }
}