<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;
use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\ContainsAssets;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryDetailData;

/**
 *
 * @SWG\Definition(required={"id", "name"})
 */
class PartialPerson implements Assetable, MlContainerInterface, \JsonSerializable, Translateable, ILiveCommentaryDetailData
{
    use ContainsAssets, ContainsMlContent;

    /**
     * Unique identifier within the system
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Human readable name of the Person
     * @var string
     * @SWG\Property(example="Cristiano Ronaldo")
     */
    private $name;

    /**
     * @SWG\Property(property="url_thumb", type="string", example="http://football-api.devks.msk.bg/assets/image.jpeg", description="150x150 face image of Person")
     * @SWG\Property(property="url_image", type="string", example="http://football-api.devks.msk.bg/assets/image.jpeg", description="277x338 full body image of Person")
     */
    protected static $assetTypes = [
        'thumb',
        'image'
    ];

    private $modelName = null;

    public function __construct($modelName = null)
    {
        $this->modelName = $modelName;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PartialPerson
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
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\SurrogateKeyInterface::setId()
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @see \Sportal\FootballApi\Asset\Assetable::getAssetTypes()
     */
    public function getAssetTypes()
    {
        return static::$assetTypes;
    }

    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name
        ];
        
        $data = $this->addAssetUrls($data);
        
        return $this->translateContent($data);
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

    public function getContainerName()
    {
        return ($this->modelName !== null) ? $this->modelName : NameUtil::persistanceName(get_class($this));
    }

    public function getAssetModelName()
    {
        return ($this->modelName !== null) ? $this->modelName : NameUtil::persistanceName(get_class($this));
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\PartialPerson
     */
    public function clonePartial()
    {
        $partial = new PartialPerson($this->getContainerName());
        $partial->setId($this->getId())
            ->setName($this->getName());
        return $partial;
    }
    
    public function getPlaceholderValue()
    {
        return $this->mlContent[$this->langCode]['name'] ?? $this->name;
    }

}

