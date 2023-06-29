<?php


namespace Sportal\FootballApi\Domain\Standing;


use Sportal\FootballApi\Domain\Standing\Specification\StandingSpecification;

class StandingModelBuilder
{
    private IStandingProfile $standingProfile;

    private IStandingModel $standingModel;

    private StandingSpecification $standingSpecification;

    /**
     * StandingModelBuilder constructor.
     * @param IStandingProfile $standingProfile
     * @param IStandingModel $standingModel
     * @param StandingSpecification $standingSpecification
     */
    public function __construct(IStandingProfile $standingProfile, IStandingModel $standingModel, StandingSpecification $standingSpecification)
    {
        $this->standingProfile = $standingProfile;
        $this->standingModel = $standingModel;
        $this->standingSpecification = $standingSpecification;
    }


    /**
     * @param IStandingProfile $standingProfile
     * @return IStandingModel
     * @throws Exception\InvalidStandingException
     */
    public function build(IStandingProfile $standingProfile): IStandingModel
    {
        $this->standingSpecification->validate($standingProfile);

        return $this->standingModel->setStanding($standingProfile);
    }
}