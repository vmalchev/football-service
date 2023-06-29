<?php
namespace Sportal\FootballApi\Model;

/**
 * Standing
 */
class Standing implements SurrogateKeyInterface
{

    const TYPE_LEAGUE = 'league';

    const TYPE_TOPSCORER = 'topscorer';

    const TYPE_CARDLIST = 'cardlist';

    const TYPE_LEAGUE_LIVE = 'league_live';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var integer
     */
    private $entityId;

    /**
     * @var integer
     */
    private $id;

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Standing
     */
    public function setType($type)
    {
        $this->type = $type;
        
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return Standing
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        
        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return Standing
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        
        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'type' => $this->type,
            'entity' => $this->entity,
            'entity_id' => $this->entityId
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'id' => $this->id
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\SurrogateKeyInterface::setId()
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}

