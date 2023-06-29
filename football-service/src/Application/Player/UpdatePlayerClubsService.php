<?php


namespace Sportal\FootballApi\Application\Player;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Player\Input\ActiveClubs\Dto;
use Sportal\FootballApi\Domain\Player\Exception\InvalidPlayerTeamException;
use Sportal\FootballApi\Domain\Player\IPlayerClubUpdateBuilder;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;

class UpdatePlayerClubsService
{

    private IPlayerRepository $playerRepository;

    private Output\Get\Teams\Mapper $outputMapper;

    private Input\ActiveTeam\Mapper $mapperClub;

    private IPlayerClubUpdateBuilder $playerClubUpdateBuilder;

    /**
     * UpdatePlayerClubsService constructor.
     * @param Output\Get\Teams\Mapper $outputMapper
     * @param IPlayerRepository $playerRepository
     * @param Input\ActiveTeam\Mapper $mapperClub
     * @param IPlayerClubUpdateBuilder $playerClubUpdateBuilder
     */
    public function __construct(Output\Get\Teams\Mapper  $outputMapper,
                                IPlayerRepository        $playerRepository,
                                Input\ActiveTeam\Mapper  $mapperClub,
                                IPlayerClubUpdateBuilder $playerClubUpdateBuilder)
    {
        $this->outputMapper = $outputMapper;
        $this->playerRepository = $playerRepository;
        $this->mapperClub = $mapperClub;
        $this->playerClubUpdateBuilder = $playerClubUpdateBuilder;
    }

    /**
     * @param Dto $clubs
     * @return ITeamPlayerEntity[]
     * @throws NoSuchEntityException
     * @throws InvalidPlayerTeamException
     */
    public function process(Dto $clubs): array
    {
        $playerEntity = $this->playerRepository->findById($clubs->getPlayerId());
        if (is_null($playerEntity)) {
            throw new NoSuchEntityException();
        }

        $playerTeamEntities = array_map(fn($club) => $this->mapperClub->map($playerEntity->getId(), $club), $clubs->getClubs());

        $activeClubs = $this->playerClubUpdateBuilder->build($playerEntity, $playerTeamEntities)->withBlacklist()->upsert()->getActivePlayers();

        return $this->outputMapper->map($activeClubs);
    }
}