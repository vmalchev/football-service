<?php


namespace Sportal\FootballApi\Domain\Standing;


use Sportal\FootballApi\Domain\Group\IGroupRepository;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Stage\IStageRepository;

class EntityResolver
{
    private array $repositoryMap;

    /**
     * EntityResolver constructor.
     * @param ISeasonRepository $seasonRepository
     * @param IStageRepository $stageRepository
     * @param IGroupRepository $groupRepository
     */
    public function __construct(ISeasonRepository $seasonRepository, IStageRepository $stageRepository, IGroupRepository $groupRepository)
    {
        $this->repositoryMap = [
            StandingEntityName::GROUP()->getValue() => $groupRepository,
            StandingEntityName::STAGE()->getValue() => $stageRepository,
            StandingEntityName::SEASON()->getValue() => $seasonRepository,
        ];
    }

    public function resolve(StandingEntityName $entityName, string $entityId): ?object
    {
        return $this->repositoryMap[$entityName->getValue()]->findById($entityId);
    }
}