<?php

namespace Sportal\FootballApi\Domain\Round;


interface IRoundEntityFactory
{

    /**
     * @param RoundType|null $type
     * @return IRoundEntityFactory
     */
    public function setType(?RoundType $type): IRoundEntityFactory;

    /**
     * @return IRoundEntity
     */
    public function create(): IRoundEntity;

    /**
     * @param string $name
     * @return IRoundEntityFactory
     */
    public function setName(string $name): IRoundEntityFactory;

    /**
     * @param string $key
     * @return IRoundEntityFactory
     */
    public function setKey(string $key): IRoundEntityFactory;

    /**
     * @param string|null $id
     * @return IRoundEntityFactory
     */
    public function setId(?string $id): IRoundEntityFactory;

    /**
     * @param \DateTimeInterface|null $startTime
     * @return IRoundEntityFactory
     */
    public function setStartDate(?\DateTimeInterface $startTime): IRoundEntityFactory;

    /**
     * @param \DateTimeInterface|null $endTime
     * @return IRoundEntityFactory
     */
    public function setEndDate(?\DateTimeInterface $endTime): IRoundEntityFactory;

    /**
     * @param RoundStatus|null $roundStatus
     * @return IRoundEntityFactory
     */
    public function setRoundStatus(?RoundStatus $roundStatus): IRoundEntityFactory;

}