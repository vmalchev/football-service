<?php


namespace Sportal\FootballApi\Infrastructure\MatchStatus;


use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntityFactory;
use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;

class MatchStatusEntityFactory implements IMatchStatusEntityFactory
{
    private ?string $id = null;
    private string $name;
    private ?string $shortName;
    private MatchStatusType $type;
    private string $code;

    /**
     * @param string|null $id
     * @return MatchStatusEntityFactory
     */
    public function setId(?string $id): MatchStatusEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return MatchStatusEntityFactory
     */
    public function setName(string $name): MatchStatusEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $shortName
     * @return MatchStatusEntityFactory
     */
    public function setShortName(?string $shortName): MatchStatusEntityFactory
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * @param MatchStatusType $type
     * @return MatchStatusEntityFactory
     */
    public function setType(MatchStatusType $type): MatchStatusEntityFactory
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $code
     * @return MatchStatusEntityFactory
     */
    public function setCode(string $code): MatchStatusEntityFactory
    {
        $this->code = $code;
        return $this;
    }

    public function create(): IMatchStatusEntity
    {
        return new MatchStatusEntity(
            $this->id,
            $this->name,
            $this->shortName,
            $this->type,
            $this->code
        );
    }

    public function setEmpty(): IMatchStatusEntityFactory
    {
        return new MatchStatusEntityFactory();
    }
}