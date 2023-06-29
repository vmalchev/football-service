<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * Object containing information about a football team, such as Manchester United, Real Madrid, etc.
 * @SWG\Definition(required={"id", "name", "national", "country"})
 */
class Team extends PartialTeam implements SurrogateKeyInterface, JsonColumnContainerInterface
{
        use ContainsJsonColumns;

    /**
     * Reference to the Country where the team is based.
     * @var \Sportal\FootballApi\Model\Country
     * @SWG\Property()
     */
    private $country;

    /**
     * @SWG\Property(property="president", type="string", description="Name of the president of the club", example="Florentino Perez")
     * @SWG\Property(property="founded", type="integer", description="Year the club was founded", example=1902)
     */

    /**
     * @var \Sportal\FootballApi\Model\Venue
     * @SWG\Property()
     */
    private $venue;

    /**
     * @var array
     */
    private $profile;

    /**
     *
     * @var \Sportal\FootballApi\Model\Coach
     * @SWG\Property()
     */
    private $coach;

    /**
     * Information about the League the team currently participates in
     * @var \Sportal\FootballApi\Model\TournamentSeason
     * @SWG\Property(property="current_league", ref="#/definitions/TournamentSeasonWithTournament")
     */
    private $league;

    /**
     * The next event the team is playing in
     * @var \Sportal\FootballApi\Model\Event
     * @SWG\Property(property="next_event")
     */
    private $nextEvent;

    /**
     *
     * @var array
     * @SWG\Property(type="object", description="Various social links for the team")
     */
    private $social;

    /**
     * @SWG\Property(property="url_home_kit", type="string", example="http://football-api.devks.msk.bg/assets/home_kit.jpeg")
     * @SWG\Property(property="url_away_kit", type="string", example="http://football-api.devks.msk.bg/assets/away_kit.jpeg")
     * @SWG\Property(property="url_squad_image", type="string", example="http://football-api.devks.msk.bg/assets/squad_image.jpeg", description="Image of the team squad")
     */
    private static $assetTypes = [
        'logo',
        'home_kit',
        'away_kit',
        'squad_image'
    ];

    private static $assetColumns = [
        'logo_filename',
        'home_kit',
        'away_kit',
        'squad_image'
    ];

    const AWAY_KIT = 'away_kit';

    const HOME_KIT = 'home_kit';

    /**
     * Set profile
     *
     * @param array $profile
     *
     * @return Team
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
     * Set country
     *
     * @param \Sportal\FootballApi\Model\Country $country
     *
     * @return Team
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
     * Set venue
     *
     * @param \Sportal\FootballApi\Model\Venue $venue
     *
     * @return Team
     */
    public function setVenue(\Sportal\FootballApi\Model\Venue $venue = null)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue
     *
     * @return \Sportal\FootballApi\Model\Venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     *
     * @return NULL|int
     */
    public function getVenueId()
    {
        return $this->venue !== null ? $this->venue->getId() : null;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'name' => $this->getName(),
            'three_letter_code' => $this->getThreeLetterCode(),
            'short_name' => $this->getShortName(),
            'national' => $this->getNational(),
            'country_id' => $this->country->getId(),
            'undecided' => $this->getUndecided(),
            'venue_id' => $this->getVenueId(),
            'profile' => ! empty($this->profile) ? json_encode($this->profile) : null,
            'social' => ! empty($this->social) ? json_encode($this->social) : null,
            'gender' => $this->getGender()
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'id' => $this->getId()
        ];
    }

    public function jsonSerialize()
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'three_letter_code' => $this->getThreeLetterCode(),
            'short_name' => $this->getShortName(),
            'national' => $this->getNational(),
            'country' => $this->country,
            'type' => $this->getType(),
            'gender' => $this->getGender()
        ];

        if ($this->getUndecided() !== null) {
            $data['undecided'] = $this->getUndecided();
        }

        if ($this->venue !== null) {
            $data['venue'] = $this->venue;
        }

        if ($this->coach !== null) {
            $data['coach'] = $this->coach;
        }

        if ($this->league !== null) {
            $data['current_league'] = $this->league;
        }

        if ($this->nextEvent !== null) {
            $data['next_event'] = $this->nextEvent;
        }

        if (! empty($this->social)) {
            $data['social'] = $this->social;
        }

        if ($this->getFormGuide() !== null) {
            $data['form'] = $this->getFormGuide();
        }

        if (! empty($this->profile)) {
            foreach ($this->profile as $key => $value) {
                $data[$key] = $value;
            }
        }

        $data = $this->translateContent($data);

        return $this->addAssetUrls($data);
    }

    /**
     * @return the array
     */
    public function getSocial()
    {
        return $this->social;
    }

    /**
     * @param array $social
     */
    public function setSocial(array $social)
    {
        $this->social = $social;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Asset\ContainsAssets::getAssetTypes()
     */
    public function getAssetTypes()
    {
        return static::$assetTypes;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\JsonColumnContainerInterface::getJsonData()
     */
    public function getJsonData()
    {
        return [
            'profile' => $this->profile,
            'social' => $this->social
        ];
    }

    public function getLogoFilename()
    {
        return $this->getAssetFilename('logo');
    }

    public static function getAssetColumns()
    {
        return static::$assetColumns;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $models = [
            $this,
            $this->country
        ];

        if ($this->venue !== null) {
            $models = array_merge($models, $this->venue->getMlContentModels());
        }

        if ($this->coach !== null) {
            $models = array_merge($models, $this->coach->getMlContentModels());
        }

        if ($this->getFormGuide() !== null) {
            foreach ($this->getFormGuide() as $form) {
                $models = array_merge($models, $form->getMlContentModels());
            }
        }

        if ($this->league !== null) {
            $models = array_merge($models, $this->league->getMlContentModels());
        }

        if ($this->nextEvent !== null) {
            $models = array_merge($models, $this->nextEvent->getMlContentModels());
        }

        return $models;
    }

    public function getCoach()
    {
        return $this->coach;
    }

    public function setCoach(\Sportal\FootballApi\Model\Coach $coach = null)
    {
        $this->coach = $coach;
        return $this;
    }

    public function getLeague()
    {
        return $this->league;
    }

    public function setLeague(TournamentSeason $league)
    {
        $this->league = $league;
        return $this;
    }

    public function getNextEvent()
    {
        return $this->nextEvent;
    }

    public function setNextEvent(\Sportal\FootballApi\Model\Event $nextEvent)
    {
        $this->nextEvent = $nextEvent;
        return $this;
    }
}

