<?php


namespace Sportal\FootballApi\Domain\Lineup;


interface ILineupModelBuilder
{
    /**
     * @param ILineupProfile $inputLineup
     * @return ILineupModel
     * @throws InvalidLineupException
     */
    public function build(ILineupProfile $inputLineup): ILineupModel;
}