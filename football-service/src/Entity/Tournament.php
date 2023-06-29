<?php

namespace Sportal\FootballApi\Entity;

class Tournament
{

    const TABLE_NAME = 'tournament';

    const ID_FIELD = 'id';

    const NAME_FIELD = 'name';

    const COUNTRY_FIELD = 'country';

    const COUNTRY_ID_FIELD = 'country_id';

    const REGIONAL_LEAGUE_FIELD = 'regional_league';

    const LOGO_FIELD = 'logo';

    const REGION = 'region';

    const TYPE = 'type';

    const GENDER = 'gender';

    private $id;

    private $name;

    /**
     *
     * @var Country
     */
    private $country;

    private $regionalLeague;

    private $logo;

    private ?string $region;

    private ?string $type;

    private ?string $gender;

    public function __construct(
        $id,
        $name,
        Country $country,
        $regionalLeague,
        $logo,
        ?string $gender,
        ?string $type,
        ?string $region
    ) {
        $this->id = (int)$id;
        $this->name = $name;
        $this->country = $country;
        $this->regionalLeague = (bool)$regionalLeague;
        $this->logo = $logo;
        $this->gender = $gender;
        $this->type = $type;
        $this->region = $region;
    }

    /**
     *
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return \Sportal\FootballApi\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     *
     * @return bool
     */
    public function isRegionalLeague()
    {
        return $this->regionalLeague;
    }

    /**
     *
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    public static function create(array $data): Tournament
    {
        return new Tournament(
            $data[static::ID_FIELD],
            $data[static::NAME_FIELD],
            Country::create($data[static::COUNTRY_FIELD]),
            $data[static::REGIONAL_LEAGUE_FIELD] ?? null,
            $data[static::LOGO_FIELD] ?? null,
            $data[static::GENDER] ?? null,
            $data[static::TYPE] ?? null,
            $data[static::REGION] ?? null
        );
    }

    public static function columns(): array
    {
        return [
            static::ID_FIELD,
            static::NAME_FIELD,
            static::COUNTRY_ID_FIELD,
            static::REGIONAL_LEAGUE_FIELD,
            static::LOGO_FIELD,
            static::GENDER,
            static::TYPE,
            static::REGION
        ];
    }
}

