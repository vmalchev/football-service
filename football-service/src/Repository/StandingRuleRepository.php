<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\StandingRule;

class StandingRuleRepository extends Repository
{

    public function createObject(array $standingRuleArr)
    {
        $standingRule = new StandingRule();
        $standingRule->setCode($standingRuleArr['code'])->setName($standingRuleArr['name']);
        if (isset($standingRuleArr['type'])) {
            $standingRule->setType($standingRuleArr['type']);
        }
        if (isset($standingRuleArr['id'])) {
            $standingRule->setId($standingRuleArr['id']);
        }
        if (isset($standingRuleArr['description'])) {
            $standingRule->setDescription($standingRuleArr['description']);
        }
        return $standingRule;
    }

    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'id' => $id
        ], [
            $this,
            'createObject'
        ]);
    }

    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter,
            [
                $this,
                'createObject'
            ]);
    }

    /**
     *
     * @param StandingRule $rule
     * @return StandingRule
     */
    public function findExisting(StandingRule $rule)
    {
        $matching = $this->queryPersistance([
            'code' => $rule->getCode()
        ], [
            $this,
            'createObject'
        ]);
        
        if (count($matching) === 1) {
            return $matching[0];
        }
        
        return null;
    }

    public function getModelClass()
    {
        return StandingRule::class;
    }
}