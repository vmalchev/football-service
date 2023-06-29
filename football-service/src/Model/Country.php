<?php

namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\ContainsAssets;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="Country", required={"id", "name"})
 */
class Country implements SurrogateKeyInterface, MlContainerInterface, \JsonSerializable, Assetable, Translateable
{

    use ContainsMlContent, ContainsAssets;

    /**
     * Unique identifier of the object within the system
     * @SWG\Property()
     * @var integer
     */
    private $id;

    /**
     * Human known name of the Country
     * @SWG\Property(example="England")
     * @var string
     */
    private $name;

    /**
     * 2 letter country code if available
     * @var string
     * @SWG\Property()
     */
    private $code;

    /**
     * @SWG\Property(property="url_flag", type="string", example="http://football-api.devks.msk.bg/assets/image.png", description="Image of the flag for the Country")
     */

    /**
     * Alternative name of the Country
     * @var string
     */
    private $alias;

    const THUMB_KEY = 'flag';

    private static $assetTypes = [
        'flag'
    ];

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Country
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     * @return Country
     */
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
        return array(
            'name' => $this->name,
            'alias' => $this->alias,
            'code' => $this->code
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

    public function jsonSerialize()
    {
        $data = array(
            'id' => $this->id,
            'name' => $this->name
        );
        if ($this->code !== null) {
            $data['code'] = $this->code;
        }
        $data = $this->translateContent($data);
        return $this->addAssetUrls($data);
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function getAssetTypes()
    {
        return static::$assetTypes;
    }

    public static function getAssetColumns()
    {
        return static::$assetTypes;
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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}

