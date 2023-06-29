<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Cache\CacheManager;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Model\StandingDataRule;

class StandingDataRuleRepository extends Repository
{

    private $standingRuleRepository;

    public function __construct(Connection $conn, CacheManager $cacheManager,
        StandingRuleRepository $standingRuleRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->standingRuleRepository = $standingRuleRepository;
    }

    public function createObject(array $arr)
    {
        $obj = new StandingDataRule();
        $obj->setStandingId($arr['standing_id'])->setStandingRule($arr['standing_rule']);
        if (isset($arr['rank'])) {
            $obj->setRank($arr['rank']);
        }
        if (isset($arr['id'])) {
            $obj->setId($arr['id']);
        }
        return $obj;
    }

    public function buildObject(array $standingDataRule)
    {
        $standingDataRule['standing_rule'] = $this->standingRuleRepository->createObject(
            $standingDataRule['standing_rule']);
        return $this->createObject($standingDataRule);
    }

    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'id' => $id
        ], [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    public function findAll($filter = [])
    {
        return $this->queryTable($this->getPersistanceName($this->getModelClass()), $filter,
            [
                $this,
                'buildObject'
            ], $this->getJoin());
    }

    /**
     *
     * @param integer $standingId
     * @return StandingDataRule[]
     */
    public function findByStanding($standingId)
    {
        return $this->findAll([
            'standing_id' => $standingId
        ]);
    }

    public function getModelClass()
    {
        return StandingDataRule::class;
    }

    public function getJoin()
    {
        static $join = null;
        if ($join === null) {
            $join = [
                [
                    'className' => $this->standingRuleRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->standingRuleRepository->getColumns()
                ]
            ];
        }
        return $join;
    }
}