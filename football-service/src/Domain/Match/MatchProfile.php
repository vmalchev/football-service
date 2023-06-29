<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntityFactory;

class MatchProfile implements IMatchProfile
{
    private IMatchEntity $matchEntity;

    private MatchMinuteResolver $minuteResolver;

    private ITeamColorsEntityFactory $colorsEntityFactory;

    /**
     * MatchProfile constructor.
     * @param MatchMinuteResolver $minuteResolver
     * @param ITeamColorsEntityFactory $colorsEntityFactory
     */
    public function __construct(MatchMinuteResolver $minuteResolver, ITeamColorsEntityFactory $colorsEntityFactory)
    {
        $this->minuteResolver = $minuteResolver;
        $this->colorsEntityFactory = $colorsEntityFactory;
    }

    /**
     * @return IMatchEntity
     */
    public function getMatch(): IMatchEntity
    {
        return $this->matchEntity;
    }

    /**
     * @param IMatchEntity $matchEntity
     * @return MatchProfile
     */
    public function setMatch(IMatchEntity $matchEntity): MatchProfile
    {
        $model = clone $this;
        $model->matchEntity = $matchEntity;
        return $model;
    }

    public function getMinute(): ?MatchMinute
    {
        return $this->minuteResolver->resolve($this->matchEntity->getStatus()->getCode(), $this->matchEntity->getPhaseStartedAt());
    }

    public function getTeamColors(): ITeamColorsEntity
    {
        $factory = $this->colorsEntityFactory->setEmpty()
            ->setEntityType(ColorEntityType::MATCH)
            ->setEntityId($this->matchEntity->getId());

        // build default colors from team entities
        $homeDefaultColor = null;
        if ($this->matchEntity->getHomeTeam() !== null && $this->matchEntity->getHomeTeam()->getColorsEntity() !== null) {
            $homeDefaultColor = $this->matchEntity->getHomeTeam()->getColorsEntity()->getColorByType(ColorTeamType::HOME());
            $factory->setColor(ColorTeamType::HOME(), $homeDefaultColor);
        }

        if ($this->matchEntity->getAwayTeam() !== null && $this->matchEntity->getAwayTeam()->getColorsEntity() !== null) {
            $awayDefaultColor = $this->matchEntity->getAwayTeam()->getColorsEntity()->getColorByType(ColorTeamType::HOME());
            $factory->setColor(ColorTeamType::AWAY(), $awayDefaultColor);
            // in case default colors for both teams are equal, override AWAY team color
            if ($homeDefaultColor !== null && $awayDefaultColor == $homeDefaultColor) {
                $factory->setColor(ColorTeamType::AWAY(), $this->matchEntity->getAwayTeam()->getColorsEntity()->getColorByType(ColorTeamType::AWAY()));
            }
        }

        // override if any from match entity
        if ($this->matchEntity->getColors() !== null) {
            $factory->setColor(ColorTeamType::HOME(), $this->matchEntity->getColors()->getColorByType(ColorTeamType::HOME()));
            $factory->setColor(ColorTeamType::AWAY(), $this->matchEntity->getColors()->getColorByType(ColorTeamType::AWAY()));
        }

        return $factory->create();
    }


}