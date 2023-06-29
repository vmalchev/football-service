<?php


namespace Sportal\FootballApi\Infrastructure\Player;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Player\IPlayerProfileEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

class PlayerProfileEntity implements \Sportal\FootballApi\Domain\Player\IPlayerProfileEntity
{

    /**
     * @var IPlayerEntity
     */
    private IPlayerEntity $playerEntity;

    /**
     * @var ITeamEntity[]
     */
    private array $teamEntities;

    /**
     * @inheritDoc
     */
    public function getPlayerEntity(): IPlayerEntity
    {
        return $this->playerEntity;
    }

    /**
     * @inheritDoc
     */
    public function getTeamPlayerEntities(): array
    {
        return $this->teamEntities;
    }

    /**
     * @inheritDoc
     */
    public function setPlayerEntity(IPlayerEntity $playerEntity): IPlayerProfileEntity
    {
        $this->playerEntity = $playerEntity;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTeamPlayerEntities(array $teamEntities): IPlayerProfileEntity
    {
        $this->teamEntities = $teamEntities;
        return $this;
    }
}