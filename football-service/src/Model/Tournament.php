<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;
use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\ContainsAssets;

/**
 * @SWG\Definition(
 *      definition="TournamentWithSeasons",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/Tournament"),
 *          @SWG\Schema(type="object", properties={
 *              @SWG\Property(property="seasons", type="array", @SWG\Items(ref="#/definitions/TournamentSeason"), description="List of seasons available for the Tournament")
 *          }, required={"seasons"})
 *      }
 * )
 *
 * @SWG\Definition(required={"id", "name", "country"},
 * description="Resource representing a Football Tournament such as A Grupa, Premier League, Champions League, World Cup, etc. Does not represent a specific season such as Premier League 2015/2016.")
 */
class Tournament implements ModelInterface, SurrogateKeyInterface, \JsonSerializable, MlContainerInterface, Assetable,
    Translateable
{
    
    use ContainsMlContent, ContainsAssets;

    /**
     * Unique Resource identifier of the Tournament
     * @SWG\Property()
     * @var integer
     */
    private $id;

    /**
     * Human readable name of the Tournament
     * @SWG\Property(example="Champions League")
     * @var string
     */
    private $name;

    /**
     * Country where the Tournament is held
     * @SWG\Property(ref="#/definitions/Country")
     * @var \Sportal\FootballApi\Model\Country
     */
    private $country;

    /**
     * Whether or not the Tournament is a regional league such as the La Liga, A PFG, EPL.
     * @SWG\Property(property="regional_league")
     * @var boolean
     */
    private $regionalLeague;

    /**
     * Represents the order position in a custom client based sorted list
     * @SWG\Property(property="client_sortorder")
     * @var integer
     */
    private $clientSortOrder;

    /**
     * @SWG\Property(property="url_logo", type="string", example="http://football-api.devks.msk.bg/assets/image.png")
     */
    const LOGO_KEY = 'logo';

    /**
     *
     * @var \Sportal\FootballApi\Model\TournamentSeason[]
     */
    private $seasons;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Shows if the tournament is Cup or a League
     * @SWG\Property(property="type", example="League", enum=TOURNAMENT_TYPE)
     * @var string;
     */
    private $type;

     /**
     * Shows if the the teams in the tournament are Male or Female
     * @SWG\Property(property="gender", example="Male", enum=TOURNAMENT_GENDER)
     * @var string;
     */
    private $gender;


    /**
     * Shows if the tournament is Regional or International
     * @SWG\Property(property="region", example="Regional", enum=TOURNAMENT_REGION)
     * @var string;
     */
    private $region;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tournament
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Tournament
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string|null $gender
     * @return $this
     */
    public function setGender(?string $gender): Tournament
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return null|string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string|null $tournamentType
     * @return $this
     */
    public function setType(?string $tournamentType): Tournament
    {
         $this->type = $tournamentType;
         return $this;
    }

    /**
     * Get tournament type (Cup/League)
     *
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param string|null $regionType
     * @return $this
     */
    public function setRegion(?string $regionType): Tournament
    {
        $this->region = $regionType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set regionalLeague
     *
     * @param boolean $regionalLeague
     *
     * @return Tournament
     */
    public function setRegionalLeague($regionalLeague)
    {
        $this->regionalLeague = (bool) $regionalLeague;
        
        return $this;
    }

    /**
     * Get regionalLeague
     *
     * @return boolean
     */
    public function getRegionalLeague()
    {
        return $this->regionalLeague;
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
     * Set id
     * @param integer $id
     * @return \Sportal\FootballApi\Model\Tournament
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set country
     *
     * @param \Sportal\FootballApi\Model\Country $country
     *
     * @return Tournament
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
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return array(
            'name' => $this->name,
            'country_id' => $this->country->getId(),
            'regional_league' => $this->regionalLeague,
            'gender' => $this->gender,
            'region' => $this->region,
            'type' => $this->type
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
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'regional_league' => (bool) ($this->regionalLeague),
            'gender' => $this->gender,
            'type' => $this->type,
            'region' => $this->region
        ];
        if ($this->seasons !== null) {
            $data['seasons'] = $this->seasons;
        }
        if ($this->clientSortOrder !== null) {
            $data['client_sortorder'] = $this->clientSortOrder;
        }
        $data = $this->translateContent($data);
        return $this->addAssetUrls($data);
    }

    public function getSeasons()
    {
        return $this->seasons;
    }

    public function setSeasons($seasons)
    {
        $this->seasons = $seasons;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Asset\ContainsAssets::getAssetTypes()
     */
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
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        return array_merge([
            $this,
            $this->country,
        ], $this->seasons ?? []);
    }

    /**
     * @return the integer
     */
    public function getClientSortOrder()
    {
        return $this->clientSortOrder;
    }

    /**
     * @param integer $clientSortOrder
     */
    public function setClientSortOrder($clientSortOrder)
    {
        $this->clientSortOrder = (integer) $clientSortOrder;
        return $this;
    }
}

