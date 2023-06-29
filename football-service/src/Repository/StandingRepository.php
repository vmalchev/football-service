<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Repository\Repository;
use Sportal\FootballApi\Model\Standing;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Model\ModelInterface;

class StandingRepository extends Repository
{

    /**
     *
     * @param array $data
     * @return \Sportal\FootballApi\Model\Standing
     */
    public function createObject(array $data)
    {
        $object = new Standing();
        $object->setType($data['type']);
        if (isset($data['model']) && $data['model'] instanceof SurrogateKeyInterface) {
            $object->setEntity($this->getPersistanceName($data['model']));
            $object->setEntityId($data['model']->getId());
        } else {
            $object->setEntity($data['entity']);
            $object->setEntityId($data['entity_id']);
        }
        if (isset($data['id'])) {
            $object->setId($data['id']);
        }
        return $object;
    }

    /**
     *
     * @param SurrogateKeyInterface $model
     * @param string $type
     * @return \Sportal\FootballApi\Model\Standing|NULL
     */
    public function findByModelType(SurrogateKeyInterface $model, $type)
    {
        $results = $this->findAll(
            [
                'entity' => $this->getPersistanceName($model),
                'entity_id' => $model->getId(),
                'type' => $type
            ]);
        if (!empty($results)) {
            return $results[0];
        }
        return null;
    }

    /**
     *
     * @param SurrogateKeyInterface $model
     * @return \Sportal\FootballApi\Model\Standing[]
     */
    public function findByModel(SurrogateKeyInterface $model)
    {
        return $this->findAll(
            [
                'entity' => $this->getPersistanceName($model),
                'entity_id' => $model->getId()
            ]);
    }

    /**
     *
     * @param SurrogateKeyInterface[] $models
     * @return \Sportal\FootballApi\Model\Standing[]
     */
    public function findByModels(array $models, $type = Standing::TYPE_LEAGUE)
    {
        if (count($models) > 0) {
            $entity = [];
            $entityIds = [];
            foreach ($models as $model) {
                $entity[$this->getPersistanceName($model)] = true;
                $entityIds[$model->getId()] = true;
            }
            return $this->findAll(
                [
                    [
                        'key' => 'entity',
                        'sign' => 'in',
                        'value' => array_keys($entity)
                    ],
                    [
                        'key' => 'entity_id',
                        'sign' => 'in',
                        'value' => array_keys($entityIds)
                    ],
                    'type' => $type
                ]);
        }
        return [];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\Standing
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
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\Standing[]
     */
    public function findAll($filter = [])
    {
        return $this->queryTable($this->getPersistanceName($this->getModelClass()), $filter,
            [
                $this,
                'createObject'
            ]);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return Standing::class;
    }
}