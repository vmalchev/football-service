<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Output\Get;

use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_TournamentGroupGetOutput")
 */
class Dto implements IDto, \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="code")
     */
    private string $code;

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

    /**
     * @var TournamentItemDto[]
     * @SWG\Property(property="tournaments")
     */
    private array $tournaments;

    public function __construct(string $code,
                                string $name,
                                ?string $description,
                                array $tournaments)
    {
        $this->code = $code;
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}