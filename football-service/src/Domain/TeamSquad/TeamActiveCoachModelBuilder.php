<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

class TeamActiveCoachModelBuilder
{
    private ITeamCoachRepository $teamCoachRepository;

    private ITeamCoachModel $teamCoachModel;

    private ITeamCoachEntityFactory $entityFactory;

    /**
     * TeamCoachModelBuilder constructor.
     * @param ITeamCoachRepository $teamCoachRepository
     * @param ITeamCoachModel $teamCoachModel
     * @param ITeamCoachEntityFactory $entityFactory
     */
    public function __construct(ITeamCoachRepository $teamCoachRepository,
                                ITeamCoachModel $teamCoachModel,
                                ITeamCoachEntityFactory $entityFactory)
    {
        $this->teamCoachRepository = $teamCoachRepository;
        $this->teamCoachModel = $teamCoachModel;
        $this->entityFactory = $entityFactory;
    }


    public function build(ITeamEntity $teamEntity, ?ICoachEntity $coach): ?ITeamCoachModel
    {
        $existingCoaches = $this->teamCoachRepository->findByTeam($teamEntity);
        $updatedCoaches = array_map(fn($coach) => $this->entityFactory->setFrom($coach)->setStatus(TeamSquadStatus::INACTIVE())->create(), $existingCoaches);
        if ($coach === null) {
            return $this->teamCoachModel->setTeamCoaches($teamEntity, $updatedCoaches);
        } else {
            $newActiveTeamCoach = $this->entityFactory->setEmpty()
                ->setCoachId($coach->getId())
                ->setCoach($coach)
                ->setStatus(TeamSquadStatus::ACTIVE())
                ->setTeamId($teamEntity->getId())
                ->create();
            $currentActiveCoach = array_filter($existingCoaches, fn($coach) => TeamSquadStatus::ACTIVE()->equals($coach->getStatus()));
            if (empty($currentActiveCoach) || $currentActiveCoach[0]->getCoachId() != $newActiveTeamCoach->getCoachId()) {
                $updatedCoaches[] = $newActiveTeamCoach;
                return $this->teamCoachModel->setTeamCoaches($teamEntity, $updatedCoaches);
            }
        }
        return null;
    }
}