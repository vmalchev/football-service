<?php

namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\ContainsAssets;
use Sportal\FootballApi\Infrastructure\Entity\ApplicationLinkEntity;
use Swagger\Annotations as SWG;

/**
 * OddProvider
 * @SWG\Definition(required={"id", "name"})
 */
class OddProvider implements SurrogateKeyInterface, \JsonSerializable, Assetable
{
    use ContainsAssets;

    const COLUMNS = [
        'id',
        'name',
        'url',
        'logo'
    ];

    /**
     * Resource identifier
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Human readable name of the OddProvider
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     * Homepage of the OddProvider
     * @var string
     * @SWG\Property()
     */
    private $url;

    /**
     * Counrty where the OddProvider is based
     * @var \Sportal\FootballApi\Model\Country
     * @SWG\Property()
     */
    private $country;

    /**
     * @var ApplicationLinkEntity[]
     */
    private $links;

    /**
     * @SWG\Property(property="url_logo", type="string", example="http://football-api.devks.msk.bg/assets/image.png")
     */
    const LOGO_KEY = 'logo';

    /**
     * Set name
     *
     * @param string $name
     *
     * @return OddProvider
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
     * Set url
     *
     * @param string $url
     *
     * @return OddProvider
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
     * @return OddProvider
     */
    public function setCountry(\Sportal\FootballApi\Model\Country $country)
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
     * @return ApplicationLinkEntity[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param ApplicationLinkEntity[] $links
     */
    public function setLinks($links)
    {
        $this->links = $links;

        if (count($this->links) === 1 && !empty($this->links[0]->getFallbackLink())) {
            $this->url = $this->links[0]->getFallbackLink();
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'country_id' => $this->country->getId()
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
            'url' => $this->url,
            'country' => $this->country,
            'links' => $this->links
        ];
        return $this->addAssetUrls($data);
    }

    public function getAssetTypes()
    {
        return [
            static::LOGO_KEY
        ];
    }

    public static function getAssetColumns()
    {
        return [
            static::LOGO_KEY
        ];
    }

    /**
     * @param array $data
     * @return OddProvider
     */
    public static function create(array $data)
    {
        $oddProvider = new OddProvider();
        $oddProvider->setId($data['id'])->setName($data['name']);
        if (isset($data['country'])) {
            $oddProvider->setCountry($data['country']);
        }
        if (isset($data['url'])) {
            $oddProvider->setUrl($data['url']);
        }
        return $oddProvider;
    }
}

