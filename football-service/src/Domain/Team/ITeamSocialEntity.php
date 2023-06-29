<?php


namespace Sportal\FootballApi\Domain\Team;


interface ITeamSocialEntity
{
    public function getWeb(): ?string;

    public function getTwitterId(): ?string;

    public function getFacebookId(): ?string;

    public function getInstagramId(): ?string;

    public function getWikipediaId(): ?string;
}