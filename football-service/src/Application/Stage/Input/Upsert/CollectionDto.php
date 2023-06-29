<?php


namespace Sportal\FootballApi\Application\Stage\Input\Upsert;


use Sportal\FootballApi\Application\IDto;
use Symfony\Component\Validator\Constraints as Assert;

class CollectionDto implements \JsonSerializable, IDto
{

    /**
     * @var Dto[]
     */
    private array $stages;

    private string $seasonId;

    public function __construct(array $stages)
    {
        $this->stages = $stages;
    }

    public function getSeasonId(): string
    {
        return $this->seasonId;
    }

    public function setSeasonId(string $seasonId): void
    {
        $this->seasonId = $seasonId;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function getStages(): array
    {
        return $this->stages;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'stages' => new Assert\Required([
                new Assert\Type('array'),
                new Assert\All([Dto::getValidatorConstraints()])
            ])
        ]);
    }

}