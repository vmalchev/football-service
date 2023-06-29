<?php


namespace Sportal\FootballApi\Infrastructure\MatchStatus;


use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;

class MatchStatusEntity implements IMatchStatusEntity
{
    private ?string $id;
    private string $name;
    private ?string $shortName;
    private MatchStatusType $type;
    private string $code;

    /**
     * MatchStatusEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param string|null $shortName
     * @param MatchStatusType $type
     * @param string $code
     */
    public function __construct(?string $id, string $name, ?string $shortName, MatchStatusType $type, string $code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->shortName = $shortName;
        $this->type = $type;
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * @return MatchStatusType
     */
    public function getType(): MatchStatusType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}