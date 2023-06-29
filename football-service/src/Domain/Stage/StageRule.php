<?php


namespace Sportal\FootballApi\Domain\Stage;


use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

class StageRule implements IStageRule
{

    private IStageRepository $stageRepository;

    public function __construct(IStageRepository $stageRepository)
    {
        $this->stageRepository = $stageRepository;
    }

    public function validate(ISeasonEntity $seasonEntity, array $stageEntities)
    {
        $updatedStages = $this->stageRepository->findBySeasonId($seasonEntity->getId());

        $existingIds = array_map(fn($stage) => $stage->getId(), $updatedStages);

        foreach ($stageEntities as $stage) {
            if ($stage->getId() === null) {
                $updatedStages[] = $stage;
            } elseif (in_array($stage->getId(), $existingIds)) {
                $position = array_search($stage->getId(), array_map(fn($stage) => $stage->getId(), $updatedStages));
                $updatedStages[$position] = $stage;
            } else {
                throw new NoSuchEntityException('Stage ' . $stage->getId());
            }
        }

        $nameArray = array_map(fn($item) => $item->getName(), $updatedStages);
        if (array_unique($nameArray) !== $nameArray) {
            $duplicateNames = array_diff_assoc($nameArray, array_unique($nameArray));
            throw new DuplicateKeyException(
                'Stage with name ' . implode(',', $duplicateNames) . ' already exists'
            );
        }

        $orderArray = array_map(fn($item) => $item->getOrderInSeason(), $updatedStages);
        if (array_unique($orderArray) !== $orderArray) {
            $duplicateOrder = array_diff_assoc($orderArray, array_unique($orderArray));
            throw new DuplicateKeyException(
                'Stage with order_in_season ' . implode(',', $duplicateOrder) . ' already exists'
            );
        }
    }

}