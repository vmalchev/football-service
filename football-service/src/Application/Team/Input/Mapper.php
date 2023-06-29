<?php

namespace Sportal\FootballApi\Application\Team\Input;

use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntityFactory;
use Sportal\FootballApi\Domain\Team\TeamGender;
use Sportal\FootballApi\Domain\Team\TeamType;
use Sportal\FootballApi\Infrastructure\Team\TeamProfileEntity;
use Sportal\FootballApi\Infrastructure\Team\TeamSocialEntity;

class Mapper
{

    private ITeamEntityFactory $teamEntityFactory;

    /**
     * @param ITeamEntityFactory $teamEntityFactory
     */
    public function __construct(ITeamEntityFactory $teamEntityFactory)
    {
        $this->teamEntityFactory = $teamEntityFactory;
    }

    public function map(TeamEditDto $dto): ITeamEntity
    {
        /** @var TeamSocialEntity|null $social */
        $social = !is_null($dto->getSocial()) ? TeamSocialEntity::fromTeamSocialDto($dto->getSocial()) : null;

        $type = TeamType::forKey($dto->getType());

        return $this->teamEntityFactory
            ->setEmpty()
            ->setName($dto->getName())
            ->setThreeLetterCode($dto->getThreeLetterCode())
            ->setIsNational($type == TeamType::NATIONAL())
            ->setIsUndecided($type == TeamType::PLACEHOLDER())
            ->setCountryId($dto->getCountryId())
            ->setVenueId($dto->getVenueId())
            ->setPresidentId($dto->getPresidentId())
            ->setSocial($social)
            ->setProfile(!empty($dto->getFounded()) != null ? new TeamProfileEntity($dto->getFounded()) : null)
            ->setGender(!is_null($dto->getGender()) ? new TeamGender($dto->getGender()) : null)
            ->setShortName($dto->getShortName())
            ->create();

    }
}