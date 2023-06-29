<?php
namespace Sportal\FootballApi\Model;

/**
 * MlContent
 */
class MlContent implements ModelInterface
{

    /**
     * @var array
     */
    private $content;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var integer
     */
    private $entityId;

    /**
     * @var string
     */
    private $languageCode;

    /**
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Set content
     *
     * @param array $content
     *
     * @return MlContent
     */
    public function setContent(array $content)
    {
        $this->content = $content;
        
        return $this;
    }

    /**
     * Get content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return MlContent
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
     * @return MlContent
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
     * Set languageCode
     *
     * @param string $languageCode
     *
     * @return MlContent
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
        
        return $this;
    }

    /**
     * Get languageCode
     *
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
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
            'content' => json_encode($this->content),
            'language_code' => $this->languageCode
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
            'language_code' => $this->languageCode
        ];
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}

