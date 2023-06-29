<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * EventPlayerType
 * @SWG\Definition(required={"category", "name", "code"})
 */
class EventPlayerType implements SurrogateKeyInterface, \JsonSerializable, MlContainerInterface, Translateable
{
    
    use ContainsMlContent;

    /**
     * Unique identifier
     * @var integer
     */
    private $id;

    /**
     * Indicates what part of the lineup the player belongs to
     * @var string
     * @SWG\Property(enum={"miss", "sub", "start", "unknown"})
     */
    private $category = 'unknown';

    /**
     * Human readable name of the type
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     * Internal unique code to identify the type
     * @var string
     * @SWG\Property()
     */
    private $code;

    /**
     * The order in which to put the event player type
     * @var integer
     */
    private $sortorder;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return EventPlayerType
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return EventPlayerType
     */
    public function setCode($code)
    {
        $this->code = $code;
        
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return EventPlayerType
     */
    public function setCategory($category)
    {
        $this->category = $category;
        
        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
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

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'name' => $this->name,
            'category' => $this->category,
            'code' => $this->code,
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

    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'code' => $this->code,
        ];

        return $this->translateContent($data);
    }

    public function getSortorder()
    {
        return $this->sortorder;
    }

    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMlContentModels()
    {
        return [
            $this
        ];
    }
}

