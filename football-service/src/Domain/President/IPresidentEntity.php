<?php


namespace Sportal\FootballApi\Domain\President;


interface IPresidentEntity
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): string;
}