<?php


namespace Sportal\FootballApi\Domain\Player;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

interface IPlayerEditEntity extends IDatabaseEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getCountryId(): string;

    public function getBirthdate(): ?DateTimeInterface;

    public function getActive(): ?bool;

    public function getProfile(): ?IPlayerProfile;

    public function getSocial(): ?IPlayerSocialEntity;

    public function getPosition(): ?PlayerPosition;

    public function getGender(): ?PersonGender;
}