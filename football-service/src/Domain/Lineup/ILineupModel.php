<?php


namespace Sportal\FootballApi\Domain\Lineup;


interface ILineupModel
{
    /**
     * @param ILineupProfile $lineupProfile
     * @return ILineupModel
     */
    public function setLineupProfile(ILineupProfile $lineupProfile): ILineupModel;

    /**
     * @return ILineupModel
     */
    public function withBlacklist(): ILineupModel;

    public function upsert(): void;

    /**
     * @return ILineupEntity
     */
    public function getLineupProfile(): ILineupProfile;
}