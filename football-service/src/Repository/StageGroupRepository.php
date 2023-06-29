<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\StageGroup;

class StageGroupRepository extends Repository
{

    public function createObject(array $stageGroupArr)
    {
        $stageGroup = (new StageGroup())->setName($stageGroupArr['name'])->setTournamentSeasonStageId(
            $stageGroupArr['tournament_season_stage_id']);

        if (isset($stageGroupArr['id'])) {
            $stageGroup->setId($stageGroupArr['id']);
        }

        if (isset($stageGroupArr['updated_at'])) {
            $stageGroup->setUpdatedAt(new \DateTime($stageGroupArr['updated_at']));
        }

        if (isset($stageGroupArr['order_in_stage'])) {
            $stageGroup->setOrderInStage($stageGroupArr['order_in_stage']);
        }

        return $stageGroup;
    }

    /**
     *
     * {@inheritDoc}
     * @return StageGroup
     * @see \Sportal\FootballApi\Repository\Repository::find()
     */
    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'id' => $id
        ], [
            $this,
            'createObject'
        ]);
    }

    /**
     *
     * @param integer $stageId
     * @return StageGroup[]
     */
    public function findByStage($stageId)
    {
        return $this->findAll([
            'tournament_season_stage_id' => $stageId
        ]);
    }

    /**
     *
     * @param StageGroup $matching
     * @return StageGroup
     */
    public function findExisting(StageGroup $matching)
    {
        $groups = $this->queryPersistance(
            [
                'name' => $matching->getName(),
                'tournament_season_stage_id' => $matching->getTournamentSeasonStageId()
            ], [
            $this,
            'createObject'
        ]);

        if (count($groups) === 1) {
            return $groups[0];
        }

        return null;
    }

    public function findAll($filter = [])
    {
        $order = [
            [
                'key' => 'order_in_stage'
            ]
        ];
        return $this->queryPersistance($filter, [
            $this,
            'createObject'
        ], [], $order);
    }

    public function getModelClass()
    {
        return StageGroup::class;
    }
}