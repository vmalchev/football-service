<?php

namespace Sportal\FootballApi\Dto\Country;

use Sportal\FootballApi\Asset\AssetURLBuilder;
use Sportal\FootballApi\Dto\Util;
use Sportal\FootballApi\Entity\Country;
use Sportal\FootballApi\Model\ContainsMlContent;
use Sportal\FootballApi\Model\MlContainerInterface;
use Sportal\FootballApi\Model\Translateable;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(required={"id", "name"}, definition="v1_CountryDto")
 */
class CountryDto implements \JsonSerializable, MlContainerInterface, Translateable
{
    use ContainsMlContent;

    /**
     *
     * @var int
     * @SWG\Property()
     */
    private $id;

    /**
     *
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     *
     * @var string
     * @SWG\Property()
     */
    private $url_flag;

    public function __construct($id, $name, $url_flag)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url_flag = $url_flag;
    }

    public static function create(Country $country)
    {
        $builder = AssetURLBuilder::getInstance();
        $url_flag = null;
        if ($builder !== null && !empty($country->getFlag())) {
            $url_flag = $builder->getAssetUrl(Country::TABLE_NAME, $country->getFlag(), 'flag');
        }
        return new CountryDto($country->getId(), $country->getName(), $url_flag);
    }

    public function jsonSerialize()
    {
        return Util::filterNull($this->translateContent(get_object_vars($this)));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMlContentModels()
    {
        return [
            $this
        ];
    }

    public function getContainerName()
    {
        return Country::TABLE_NAME;
    }
}

