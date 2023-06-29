<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * EventStatus
 * @SWG\Definition(required={"type", "name", "code"})
 */
class EventStatus implements SurrogateKeyInterface, MlContainerInterface, \JsonSerializable, Translateable
{
    
    use ContainsMlContent;

    const TYPE_FINISHED = 'finished';

    const TYPE_NOTSTARTED = 'notstarted';

    const TYPE_INPROGRESS = 'inprogress';

    const TYPE_INTERRUPTED = 'interrupted';

    const TYPE_CANCELLED = 'cancelled';

    const CODE_INTERRUPTED = 'interrupted';

    const TYPES = [
        self::TYPE_FINISHED,
        self::TYPE_NOTSTARTED,
        self::TYPE_INPROGRESS,
        self::TYPE_INTERRUPTED,
        self::TYPE_CANCELLED
    ];

    /**
     * Unique identifier of the status
     * @var integer
     */
    private $id;

    /**
     * Status classification
     * @var string
     * @SWG\Property(enum={"finished", "cancelled", "notstarted", "interrupted", "inprogress"})
     */
    private $type;

    /**
     * Human readable name describing the status, can be translated
     * @var string
     * @SWG\Property(example="Finished")
     */
    private $name;

    /**
     * Human readable short name describing the status, can be translated
     * @var string
     * @SWG\Property(example="FIN")
     */
    private $short_name;

    /**
     * Unique string used to identify the status
     * @var string
     * @SWG\Property(enum={"finished", "not_started", "1st_half", "2nd_half"})
     */
    private $code;

    /**
     * Set type
     *
     * @param string $type
     *
     * @return EventStatus
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
     * Set subtype
     *
     * @param string $name
     *
     * @return EventStatus
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get subtype
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set short_name
     *
     * @param string $short_name
     *
     * @return EventStatus
     */
    public function setShortName($short_name)
    {
        $this->short_name = $short_name;

        return $this;
    }

    /**
     * Get short_name
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->short_name;
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
        return array(
            'name' => $this->name,
            'type' => $this->type,
            'code' => $this->code,
            'short_name' => $this->short_name
        );
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return array(
            'id' => $this->id
        );
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

    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'code' => $this->code
        ];

        return $this->translateContent($data);
    }

    public function isFinished()
    {
        return $this->type == 'finished';
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function isLive()
    {
        return $this->type == static::TYPE_INPROGRESS || $this->code == static::CODE_INTERRUPTED;
    }
    
    public function isNotStarted()
    {
        return $this->type == static::TYPE_NOTSTARTED;
    }

    public static function getLiveTypes()
    {
        return [
            static::TYPE_INPROGRESS
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        return [
            $this
        ];
    }
}

