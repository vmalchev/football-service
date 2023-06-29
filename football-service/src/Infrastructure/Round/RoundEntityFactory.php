<?php

namespace Sportal\FootballApi\Infrastructure\Round;

use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Round\IRoundEntityFactory;
use Sportal\FootballApi\Domain\Round\RoundStatus;
use Sportal\FootballApi\Domain\Round\RoundType;

class RoundEntityFactory implements IRoundEntityFactory
{

    private ?string $id;

    private string $key;

    private string $name;

    private ?RoundType $type;

    private ?\DateTimeInterface $startDate = null;

    private ?\DateTimeInterface $endDate = null;

    private ?RoundStatus $roundStatus = null;

    /**
     * @inheritDoc
     */
    public function setId(?string $id): IRoundEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setKey(string $key): IRoundEntityFactory
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): IRoundEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setType(?RoundType $type): IRoundEntityFactory
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setStartDate(?\DateTimeInterface $startDate): IRoundEntityFactory
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEndDate(?\DateTimeInterface $endDate): IRoundEntityFactory
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRoundStatus(?RoundStatus $roundStatus): IRoundEntityFactory
    {
        $this->roundStatus = $roundStatus;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function create(): IRoundEntity
    {
        return new RoundEntity(
            $this->id,
            $this->key,
            $this->name,
            $this->type ?? null,
            $this->startDate,
            $this->endDate,
            $this->roundStatus
        );
    }
}