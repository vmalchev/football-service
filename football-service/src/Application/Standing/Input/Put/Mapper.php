<?php


namespace Sportal\FootballApi\Application\Standing\Input\Put;


use Sportal\FootballApi\Domain\Standing\IStandingEntityFactory;
use Sportal\FootballApi\Domain\Standing\IStandingProfile;
use Sportal\FootballApi\Domain\Standing\StandingEntityName;
use Sportal\FootballApi\Domain\Standing\StandingType;

class Mapper
{
    private IStandingEntityFactory $entityFactory;

    private League\Mapper $leagueMapper;

    private TopScorer\Mapper $topScorerMapper;

    private IStandingProfile $standingProfile;

    /**
     * Mapper constructor.
     * @param IStandingEntityFactory $entityFactory
     * @param League\Mapper $leagueMapper
     * @param TopScorer\Mapper $topScorerMapper
     * @param IStandingProfile $standingProfile
     */
    public function __construct(IStandingEntityFactory $entityFactory,
                                League\Mapper $leagueMapper,
                                TopScorer\Mapper $topScorerMapper,
                                IStandingProfile $standingProfile)
    {
        $this->entityFactory = $entityFactory;
        $this->leagueMapper = $leagueMapper;
        $this->topScorerMapper = $topScorerMapper;
        $this->standingProfile = $standingProfile;
    }


    public function map(Dto $dto): IStandingProfile
    {
        $standingEntity = $this->entityFactory->setEmpty()
            ->setEntityName(StandingEntityName::forKey($dto->getEntity()))
            ->setType($dto->getType())
            ->setEntityId($dto->getEntityId())
            ->create();

        if (StandingType::LEAGUE()->equals($standingEntity->getType())) {
            $entries = array_map([$this->leagueMapper, 'map'], $dto->getEntries());
        } else {
            $entries = array_map([$this->topScorerMapper, 'map'], $dto->getEntries());
        }
        return $this->standingProfile->setStandingEntity($standingEntity)->setEntries($entries);
    }

}