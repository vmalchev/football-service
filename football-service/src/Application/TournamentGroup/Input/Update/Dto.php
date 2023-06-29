<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Input\Update;

use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\TournamentGroup\Input\Insert\TournamentItemDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_TournamentGroupUpdateInput")
 */
class Dto implements IDto, \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="name")
     */
    private string $name;

    /**
     * @var string|null
     * @SWG\Property(property="description")
     */
    private ?string $description;

    private string $code;

    /**
     * @var TournamentItemDto[]
     * @SWG\Property(property="tournaments")
     */
    private array $tournaments;

    public function __construct(string $name,
                                ?string $description,
                                array $tournaments)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tournaments = $tournaments;
    }

    /**
     * @return TournamentItemDto[]
     */
    public function getTournaments(): array
    {
        return $this->tournaments;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Type('string')
            ],
            'description' => [
                new Assert\Optional([
                    new Assert\Type('string'),
                    new Assert\Length(['max' => 255])
                ])
            ],
            'tournaments' => new Assert\All([
                TournamentItemDto::getValidatorConstraints()
            ])
        ]);
    }

}