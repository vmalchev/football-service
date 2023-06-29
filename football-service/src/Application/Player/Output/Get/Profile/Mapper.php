<?php


namespace Sportal\FootballApi\Application\Player\Output\Get\Profile;


use Sportal\FootballApi\Domain\Player\IPlayerProfileEntity;

class Mapper
{

    private \Sportal\FootballApi\Application\Player\Output\Get\Mapper $playerMapper;
    private \Sportal\FootballApi\Application\Player\Output\Get\Teams\Mapper $teamsMapper;

    /**
     * Mapper constructor.
     * @param \Sportal\FootballApi\Application\Player\Output\Get\Mapper $playerMapper
     * @param \Sportal\FootballApi\Application\Player\Output\Get\Teams\Mapper $teamsMapper
     */
    public function __construct(\Sportal\FootballApi\Application\Player\Output\Get\Mapper $playerMapper,
                                \Sportal\FootballApi\Application\Player\Output\Get\Teams\Mapper $teamsMapper)
    {
        $this->playerMapper = $playerMapper;
        $this->teamsMapper = $teamsMapper;
    }

    /**
     * @param IPlayerProfileEntity $playerProfileEntity
     * @return Dto
     */
    public function map(IPlayerProfileEntity $playerProfileEntity): Dto
    {
        $playerDto = $this->playerMapper->map($playerProfileEntity->getPlayerEntity());
        $teamsDto = $this->teamsMapper->map($playerProfileEntity->getTeamPlayerEntities());

        return new Dto($playerDto, $teamsDto);
    }
}