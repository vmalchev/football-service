<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Database\ModelInterface;
use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Cache\Entity\EntityManager;

trait UpdatesModels
{

    public function create($lineup)
    {
        $this->getDb()->insert($lineup);
        $this->getEm()->add($lineup);
    }

    public function update($existing, $updated)
    {
        $this->getDb()->update($updated);
        $this->getEm()->update($existing, $updated);
    }

    public function delete($model)
    {
        $this->getEm()->delete($model);
        $this->getDb()->delete($model);
    }

    public function flush()
    {
        $this->getDb()->flush();
        $this->getEm()->flush();
    }

    public function getChanges(ModelInterface $existing, ModelInterface $updated)
    {
        $changed = [];
        $updatedMap = $updated->getPersistanceMap();
        foreach ($existing->getPersistanceMap() as $key => $value) {
            if ($updatedMap[$key] != $value) {
                $changed[] = $key;
            }
        }
        
        return $changed;
    }

    /**
     * @return Database
     */
    abstract protected function getDb();

    /**
     * @return EntityManager
     */
    abstract protected function getEm();
}