<?php


namespace Sportal\FootballApi\Infrastructure\Player;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Player\IPlayerProfileBuilder;
use Sportal\FootballApi\Domain\Player\IPlayerProfileEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerRepository;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class PlayerProfileBuilder implements IPlayerProfileBuilder
{

    private IPlayerProfileEntity $playerProfileEntity;

    private ITeamPlayerRepository $teamPlayerRepository;

    /**
     * PlayerTeamBuilder constructor.
     * @param IPlayerProfileEntity $playerProfileEntity
     * @param ITeamPlayerRepository $teamPlayerRepository
     */
    public function __construct(IPlayerProfileEntity $playerProfileEntity, ITeamPlayerRepository $teamPlayerRepository)
    {
        $this->playerProfileEntity = $playerProfileEntity;
        $this->teamPlayerRepository = $teamPlayerRepository;
    }

    public function build(IPlayerEntity $playerEntity): IPlayerProfileEntity
    {
        $teamEntities = $this->teamPlayerRepository->findByPlayer($playerEntity->getId(), TeamSquadStatus::ACTIVE());

        return $this->playerProfileEntity
            ->setPlayerEntity($playerEntity)
            ->setTeamPlayerEntities($teamEntities);
    }
}