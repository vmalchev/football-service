<?php

namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\ContainsAssets;
use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryDetailData;
use Swagger\Annotations as SWG;

/**
 * Venue
 * @SWG\Definition(required={"id", "name"})
 */
class Venue implements SurrogateKeyInterface, MlContainerInterface, \JsonSerializable, JsonColumnContainerInterface,
    Assetable, Translateable, ILiveCommentaryDetailData
{
    use ContainsMlContent, ContainsJsonColumns, ContainsAssets;

    /**
     * Unique identifier in the system
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Human readable name of the Venue
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     * Country where the stadium is located
     * @var \Sportal\FootballApi\Model\Country
     * @SWG\Property()
     */
    private $country;

    /**
     * @deprecated use $cityModel
     * City where the stadiums is located
     * @var string
     * @SWG\Property()
     */
    private $city;

    /**
     * @var array
     */
    private $profile;


    private $cityId;

    private $cityModel;


    /**
     * @SWG\Property(property="url_image", type="string", example="http://football-api.devks.msk.bg/assets/image.jpeg", description="600x450 image of the Venue")
     * @SWG\Property(property="capacity", type="integer", description="Total capacity of the stadium")
     * @SWG\Property(property="lat", type="number", format="float", description="Latitude coordinate of the stadium's location")
     * @SWG\Property(property="lng", type="number", format="float", description="Longitude coordinate of the stadium's location")
     */
    private static $assetTypes = [
        'image'
    ];

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Venue
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
     * @param string $city
     *
     * @return Venue
     * @deprecated use setCityModel(City $city)
     * Set city
     *
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     * @deprecated use getCityModel()
     * Get city
     *
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set profile
     *
     * @param array $profile
     *
     * @return Venue
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return array
     */
    public function getProfile()
    {
        return $this->profile;
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
     * Set country
     *
     * @param \Sportal\FootballApi\Model\Country $country
     *
     * @return Venue
     */
    public function setCountry(\Sportal\FootballApi\Model\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Sportal\FootballApi\Model\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * @return City
     */
    public function getCityModel(): ?City
    {
        return $this->cityModel;
    }

    /**
     * @param mixed $cityId
     * @return Venue
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
        return $this;
    }

    public function setCityModel(?City $city)
    {
        $this->cityModel = $city;
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
            'city_id' => $this->cityId,
            'country_id' => $this->country->getId(),
            'profile' => json_encode($this->profile),
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
            'name' => $this->name
        ];

        if (isset($this->cityModel)) {
            $data['city'] = $this->cityModel->getTranslatedName();
        } else if (isset($this->city)) {
            $data['city'] = $this->city;
        }

        if (isset($this->country)) {
            $data['country'] = $this->country;
        }

        if (!empty($this->profile)) {
            $data = $this->mergeJsonData($data);
        }

        $data = $this->translateContent($data);

        return $this->addAssetUrls($data);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\JsonColumnContainerInterface::getJsonData()
     */
    public function getJsonData()
    {
        return [
            'profile' => $this->profile
        ];
    }

    public function getCapacity()
    {
        if (!empty($this->profile) && isset($this->profile['capacity'])) {
            return $this->profile['capacity'];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Asset\ContainsAssets::getAssetTypes()
     */
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
        $models = [
            $this
        ];

        if ($this->country !== null) {
            $models[] = $this->country;
        }

        if ($this->cityModel !== null) {
            $models[] = $this->cityModel;
        }

        return $models;
    }

    public function clonePartial()
    {
        return (new Venue())->setId($this->getId())
            ->setName($this->getName());
    }

    public function getPlaceholderValue()
    {
        return $this->mlContent[$this->langCode]['name'] ?? $this->name;
    }


}

