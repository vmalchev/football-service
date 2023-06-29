<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use JsonSerializable;
use Sportal\FootballApi\Domain\Team\ITeamProfileEntity;

class TeamProfileEntity implements JsonSerializable, ITeamProfileEntity
{
    private ?int $founded;

    /**
     * TeamProfileEntity constructor.
     * @param int|null $founded
     */
    public function __construct(?int $founded = null)
    {
        $this->founded = $founded;
    }

    public static function fromArray(array $properties): ITeamProfileEntity
    {
        $teamSocialEntity = new TeamProfileEntity();

        foreach ($properties as $propertyName => $propertyValue) {
            $teamSocialEntity->{$propertyName} = $propertyValue;
        }

        return $teamSocialEntity;
    }

    /**
     * @return int|null
     */
    public function getFounded(): ?int
    {
        return $this->founded;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($property) => !is_null($property));
    }
}