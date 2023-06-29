<?php
namespace Sportal\FootballApi\Dto\Tournament;

use Sportal\FootballApi\Dto\Country\CountryDto;
use Sportal\FootballApi\Entity\Tournament;
use Sportal\FootballApi\Dto\Util;
use Sportal\FootballApi\Model\Translateable;
use Sportal\FootballApi\Model\MlContainerInterface;
use Sportal\FootballApi\Model\ContainsMlContent;
use Sportal\FootballApi\Asset\AssetURLBuilder;

/**
 * @SWG\Definition(required={"id", "name", "country"},
 * description="Resource representing a Football Tournament such as A Grupa, Premier League, Champions League, World Cup, etc. Does not represent a specific season such as Premier League 2015/2016.")
 */
class TournamentDto implements \JsonSerializable, Translateable, MlContainerInterface
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
     * @var CountryDto
     * @SWG\Property()
     */
    private $country;

    /**
     *
     * @var bool
     * @SWG\Property()
     */
    private $regional_league;

    /**
     *
     * @var string
     * @SWG\Property()
     */
    private $url_logo;

    public function __construct($id, $name, CountryDto $country, $regional_league, $url_logo)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->regional_league = $regional_league;
        $this->url_logo = $url_logo;
    }

    public function jsonSerialize()
    {
        return Util::filterNull($this->translateContent(get_object_vars($this)));
    }

    public static function create(Tournament $tournament, CountryDto $countryDto)
    {
        $builder = AssetURLBuilder::getInstance();
        $urlLogo = null;
        if ($builder !== null && !empty($tournament->getLogo())) {
            $urlLogo = $builder->getAssetUrl(Tournament::TABLE_NAME, $tournament->getLogo(), 'logo');
        }
        return new TournamentDto($tournament->getId(), $tournament->getName(), $countryDto, $tournament->isRegionalLeague(), $urlLogo);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContainerName()
    {
        return Tournament::TABLE_NAME;
    }

    public function getMlContentModels()
    {
        return [
            $this,
            $this->country
        ];
    }
}