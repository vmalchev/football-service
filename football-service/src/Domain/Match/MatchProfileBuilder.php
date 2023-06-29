<?php


namespace Sportal\FootballApi\Domain\Match;


class MatchProfileBuilder implements IMatchProfileBuilder
{
    private IMatchProfile $matchProfile;

    /**
     * MatchModelBuilder constructor.
     * @param IMatchProfile $matchModel
     */
    public function __construct(IMatchProfile $matchModel)
    {
        $this->matchProfile = $matchModel;
    }


    public function build(IMatchEntity $matchEntity): IMatchProfile
    {
        return $this->matchProfile->setMatch($matchEntity);
    }
}