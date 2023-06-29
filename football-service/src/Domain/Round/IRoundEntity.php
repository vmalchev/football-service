<?php

namespace Sportal\FootballApi\Domain\Round;


interface IRoundEntity
{

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return RoundType|null
     */
    public function getType(): ?RoundType;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface;

    /**
     * @return RoundStatus|null
     */
    public function getRoundStatus(): ?RoundStatus;

}