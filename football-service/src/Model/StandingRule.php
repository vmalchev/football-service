<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * StandingRule
 * @SWG\Definition(required={"name", "code", "type"})
 */
class StandingRule implements SurrogateKeyInterface, \JsonSerializable, Translateable, MlContainerInterface
{

    use ContainsMlContent;
    /**
     * Human readable name describing the standing rule
     * @var string
     * @SWG\Property(example="Champions League")
     */
    private $name;

    /**
     * Unique code identifying the standing rule
     * @var string
     * @SWG\Property(example="championsleague")
     */
    private $code;

    /**
     * Type classification of the standing rule
     * @var string
     * @SWG\Property(enum={"promotion", "promotion_playoff", "relegation", "relegation_playoff", "tiertwo", "tiertwo_playoff"})
     */
    private $type;

    /**
     * Detailed description of the standing rule
     * @var string
     * @SWG\Property()
     */
    private $description;

    /**
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return StandingRule
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
     * @return StandingRule
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
     * Set description
     *
     * @param string $description
     *
     * @return StandingRule
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type
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

    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
        ];
        if ($this->type !== null) {
            $data['type'] = $this->type;
        }
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        $data = $this->translateContent($data);

        return $data;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getMlContentModels()
    {
        return [
            $this
        ];
    }
}

