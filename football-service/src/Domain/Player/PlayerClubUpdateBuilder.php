<?php


namespace Sportal\FootballApi\Domain\Player;


use Sportal\FootballApi\Domain\Player\Exception\InvalidPlayerTeamException;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Domain\Team\TeamType;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntityFactory;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerModel;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerRepository;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class PlayerClubUpdateBuilder implements IPlayerClubUpdateBuilder
{

    private ITeamPlayerEntityFactory $teamPlayerEntityFactory;

    private ITeamPlayerRepository $playerTeamsRepository;

    private ITeamRepository $teamRepository;

    private ITeamPlayerModel $playerTeamsModel;

    /**
     * PlayerClubUpdateBuilder constructor.
     * @param ITeamPlayerRepository $playerTeamsRepository
     * @param ITeamPlayerEntityFactory $teamPlayerEntityFactory
     * @param ITeamRepository $teamRepository
     * @param ITeamPlayerModel $playerTeamsModel
     */
    public function __construct(ITeamPlayerRepository $playerTeamsRepository,
                                ITeamPlayerEntityFactory $teamPlayerEntityFactory,
                                ITeamRepository $teamRepository,
                                ITeamPlayerModel $playerTeamsModel)
    {
        $this->playerTeamsRepository = $playerTeamsRepository;
        $this->teamPlayerEntityFactory = $teamPlayerEntityFactory;
        $this->teamRepository = $teamRepository;
        $this->playerTeamsModel = $playerTeamsModel;
    }

    /**
     * @param IPlayerEntity $playerEntity
     * @param array $newClubs
     * @return ITeamPlayerModel
     * @throws InvalidPlayerTeamException
     */
    public function build(IPlayerEntity $playerEntity, array $newClubs): ITeamPlayerModel
    {
        $existingClubs = $this->playerTeamsRepository->findByPlayer($playerEntity->getId(), TeamSquadStatus::ACTIVE());

        $updatedTeams = [];
        foreach ($existingClubs as $existingClub) {
            if ($existingClub->getTeam() != null && TeamType::CLUB()->equals($existingClub->getTeam()->getType())) {
                $updatedTeams[] = $this->teamPlayerEntityFactory->setFrom($existingClub)
                    ->setStatus(TeamSquadStatus::INACTIVE())
                    ->create();
            }
        }

        return $this->playerTeamsModel->setTeamPlayers(array_merge($updatedTeams, $this->getValidClubs($newClubs)))->setUpsertByTeam(false);
    }

    /**
     * @param ITeamPlayerEntity[] $newClubs
     * @return ITeamPlayerEntity[]
     * @throws InvalidPlayerTeamException
     */
    private function getValidClubs(array $newClubs): array
    {
        $validClubs = [];

        foreach ($newClubs as $newClub) {
            $team = $this->teamRepository->findById($newClub->getTeamId());

            if (!is_null($team) && TeamType::CLUB()->equals($team->getType())) {
                $validClubs[] = $this->teamPlayerEntityFactory->setFrom($newClub)
                    ->setTeam($team)
                    ->create();
            } else {
                throw new InvalidPlayerTeamException("Invalid club ID: {$newClub->getTeamId()}");
            }
        }

        $uniqueTeamIds = array_unique(array_map(fn($team) => $team->getTeamId(), $validClubs));
        if (count($uniqueTeamIds) != count($validClubs)) {
            throw new InvalidPlayerTeamException("Duplicate team IDs.");
        }

        return $validClubs;
    }
}