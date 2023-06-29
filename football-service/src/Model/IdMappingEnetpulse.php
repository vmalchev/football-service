<?php
namespace Sportal\FootballApi\Model;

/**
 * IdMappingEnetpulse
 */
class IdMappingEnetpulse implements ModelInterface
{

    /**
     * @var integer
     */
    private $enetpulseId;

    /**
     * @var integer
     */
    private $revision;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var integer
     */
    private $entityId;

    /**
     * Set enetpulseId
     *
     * @param integer $enetpulseId
     *
     * @return IdMappingEnetpulse
     */
    public function setEnetpulseId($enetpulseId)
    {
        $this->enetpulseId = $enetpulseId;
        
        return $this;
    }

    /**
     * Get enetpulseId
     *
     * @return integer
     */
    public function getEnetpulseId()
    {
        return $this->enetpulseId;
    }

    /**
     * Set revision
     *
     * @param integer $revision
     *
     * @return IdMappingEnetpulse
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
        
        return $this;
    }

    /**
     * Get revision
     *
     * @return integer
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return IdMappingEnetpulse
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
     * @return IdMappingEnetpulse
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
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'entity' => $this->entity,
            'entity_id' => $this->entityId,
            'enetpulse_id' => $this->enetpulseId,
            'revision' => $this->revision
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'entity' => $this->entity,
            'entity_id' => $this->entityId,
            'enetpulse_id' => $this->enetpulseId
        ];
    }
}

