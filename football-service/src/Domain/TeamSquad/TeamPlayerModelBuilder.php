<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\TeamSquad\Exception\InvalidTeamSquadException;
use Sportal\FootballApi\Domain\TeamSquad\Specification\TeamPlayerSpecification;

class TeamPlayerModelBuilder
{
    private ITeamPlayerModel $teamPlayerModel;

    private IPlayerRepository $playerRepository;

    private ITeamPlayerEntityFactory $teamPlayerFactory;

    private TeamPlayerSpecification $teamPlayerSpecification;

    /**
     * TeamPlayerModelBuilder constructor.
     * @param ITeamPlayerModel $teamPlayerModel
     * @param IPlayerRepository $playerRepository
     * @param ITeamPlayerEntityFactory $teamPlayerFactory
     * @param TeamPlayerSpecification $teamPlayerSpecification
     */
    public function __construct(ITeamPlayerModel $teamPlayerModel, IPlayerRepository $playerRepository, ITeamPlayerEntityFactory $teamPlayerFactory, TeamPlayerSpecification $teamPlayerSpecification)
    {
        $this->teamPlayerModel = $teamPlayerModel;
        $this->playerRepository = $playerRepository;
        $this->teamPlayerFactory = $teamPlayerFactory;
        $this->teamPlayerSpecification = $teamPlayerSpecification;
    }

    /**
     * @param ITeamEntity $teamEntity
     * @param ITeamPlayerEntity[] $teamPlayers
     * @return ITeamPlayerModel
     * @throws InvalidTeamSquadException
     */
    public function build(ITeamEntity $teamEntity, array $teamPlayers): ITeamPlayerModel
    {
        $playerIds = array_map(fn(ITeamPlayerEntity $entity) => $entity->getPlayerId(), $teamPlayers);
        $updatedTeamPlayers = [];
        if (!empty($playerIds)) {
            $playerCollection = $this->playerRepository->findByIds($playerIds);
            foreach ($teamPlayers as $teamPlayer) {
                $player = $playerCollection->getById($teamPlayer->getPlayerId());
                if ($player === null) {
                    throw new InvalidTeamSquadException("Non existing player:{$teamPlayer->getPlayerId()}");
                }
                $updatedTeamPlayers[] = $this->teamPlayerFactory->setFrom($teamPlayer)
                    ->setId(null) // make sure to create a new entry and not update existing
                    ->setPlayer($player)
                    ->setTeamId($teamEntity->getId())
                    ->create();
            }
        }
        $this->teamPlayerSpecification->validate($teamPlayers);
        return $this->teamPlayerModel
            ->setTeamEntity($teamEntity)
            ->setTeamPlayers($updatedTeamPlayers)
            ->setUpsertByTeam(true);
    }

}