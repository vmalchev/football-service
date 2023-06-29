<?php


namespace Sportal\FootballApi\Domain\Lineup;


use Sportal\FootballApi\Application\Lineup;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Coach\ICoachRepository;

class LineupModelBuilder implements ILineupModelBuilder
{
    private ILineupModel $lineupModel;

    private LineupPlayerBuilder $playerBuilder;

    private ILineupEntityFactory $lineupFactory;

    private ICoachRepository $coachRepository;

    private LineupSpecification $lineupSpecification;

    private ILineupProfile $lineupProfile;

    /**
     * LineupModelBuilder constructor.
     * @param ILineupModel $lineupModel
     * @param LineupPlayerBuilder $playerBuilder
     * @param ILineupEntityFactory $lineupFactory
     * @param ICoachRepository $coachRepository
     * @param LineupSpecification $lineupSpecification
     * @param ILineupProfile $lineupProfile
     */
    public function __construct(ILineupModel $lineupModel,
                                LineupPlayerBuilder $playerBuilder,
                                ILineupEntityFactory $lineupFactory,
                                ICoachRepository $coachRepository,
                                LineupSpecification $lineupSpecification,
                                ILineupProfile $lineupProfile)
    {
        $this->lineupModel = $lineupModel;
        $this->playerBuilder = $playerBuilder;
        $this->lineupFactory = $lineupFactory;
        $this->coachRepository = $coachRepository;
        $this->lineupSpecification = $lineupSpecification;
        $this->lineupProfile = $lineupProfile;
    }

    /**
     * @param ILineupProfile $inputLineup
     * @return ILineupModel
     * @throws InvalidLineupException
     */
    public function build(ILineupProfile $inputLineup): ILineupModel
    {
        $homeCoach = $this->buildCoach($inputLineup->getLineup()->getHomeCoachId());
        $awayCoach = $this->buildCoach($inputLineup->getLineup()->getAwayCoachId());
        $lineupEntity = $this->lineupFactory->setEntity($inputLineup->getLineup())
            ->setHomeCoach($homeCoach)
            ->setAwayCoach($awayCoach)
            ->create();

        $allPlayers = $this->playerBuilder->build($inputLineup->getPlayers());
        $updatedProfile = $this->lineupProfile->setLineup($lineupEntity)->setPlayers($allPlayers);
        $this->lineupSpecification->validate($updatedProfile);
        return $this->lineupModel->setLineupProfile($updatedProfile);
    }

    /**
     * @param string|null $coachId
     * @return ICoachEntity|null
     * @throws InvalidLineupException
     */
    private function buildCoach(?string $coachId): ?ICoachEntity
    {
        if (!is_null($coachId)) {
            $coach = $this->coachRepository->findById($coachId);
            if (is_null($coach)) {
                throw new InvalidLineupException("Coach with id: $coachId does not exist");
            }
            return $coach;
        } else {
            return null;
        }
    }
}