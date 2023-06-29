<?php

namespace Sportal\FootballApi\Infrastructure\Entity;

use Sportal\FootballApi\Domain\Country\ICountryEntity;

class CountryEntity implements ICountryEntity
{
    const TABLE_NAME = 'country';

    const ID_FIELD = 'id';

    const NAME_FIELD = 'name';

    const CODE_FIELD = 'code';

    private string $id;

    private string $name;

    private ?string $code;

    public function __construct(string $id, string $name, ?string $code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
    }

    public static function create(array $data): CountryEntity
    {
        return new CountryEntity(
            $data[static::ID_FIELD],
            $data[static::NAME_FIELD],
            $data[static::CODE_FIELD] ?? null
        );
    }

    public static function columns(): array
    {
        return [
            static::ID_FIELD,
            static::NAME_FIELD,
            static::CODE_FIELD,
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
}

