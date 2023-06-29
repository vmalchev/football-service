<?php

namespace Sportal\FootballApi\Infrastructure\Round;

use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Round\RoundStatus;
use Sportal\FootballApi\Domain\Round\RoundType;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class RoundEntity extends GeneratedIdDatabaseEntity implements IRoundEntity, IDatabaseEntity
{

    /**
     * @var string|null
     */
    private ?string $id;

    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var RoundType|null
     */
    private ?RoundType $type;

    /**
     * @var \DateTimeInterface|null
     */
    private ?\DateTimeInterface $startDate;

    /**
     * @var \DateTimeInterface|null
     */
    private ?\DateTimeInterface $endDate;

    /**
     * @var RoundStatus|null
     */
    private ?RoundStatus $roundStatus;

    /**
     * @param string|null $id
     * @param string $key
     * @param string $name
     * @param RoundType|null $type
     * @param \DateTimeInterface|null $startDate
     * @param \DateTimeInterface|null $endDate
     * @param RoundStatus|null $roundStatus
     */
    public function __construct(?string $id,
                                string $key,
                                string $name,
                                ?RoundType $type,
                                ?\DateTimeInterface $startDate,
                                ?\DateTimeInterface $endDate,
                                ?RoundStatus $roundStatus)
    {
        $this->key = $key;
        $this->name = $name;
        $this->type = $type;
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->roundStatus = $roundStatus;
    }

    /**
     * @inheritDoc
     */
    public function getType(): ?RoundType
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @inheritDoc
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @inheritDoc
     */
    public function getRoundStatus(): ?RoundStatus
    {
        return $this->roundStatus;
    }

    public function getDatabaseEntry(): array
    {
        return [
            RoundTableMapper::FIELD_KEY => $this->getKey(),
            RoundTableMapper::FIELD_NAME => $this->getName(),
            RoundTableMapper::FIELD_TYPE => $this->getType()
        ];
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $entity = clone $this;
        $entity->id = $id;
        return $entity;
    }
}