<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;

use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutGroupEntityFactory;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutRoundEntityFactory;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeEntityFactory;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Infrastructure\Team\TeamEntity;
use Sportal\FootballApi\Infrastructure\Team\TeamTableMapper;

class Mapper
{
    private IKnockoutRoundEntityFactory $roundEntityFactory;
    private IKnockoutSchemeEntityFactory $schemeFactory;
    private IKnockoutGroupEntityFactory $factory;
    private MatchMapper $matchMapper;
    private TeamTableMapper $tableMapper;

    /**
     * Mapper constructor.
     * @param IKnockoutRoundEntityFactory $roundEntityFactory
     * @param IKnockoutSchemeEntityFactory $schemeFactory
     * @param IKnockoutGroupEntityFactory $factory
     * @param MatchMapper $matchMapper
     * @param TeamTableMapper $tableMapper
     */
    public function __construct(
        IKnockoutRoundEntityFactory $roundEntityFactory,
        IKnockoutSchemeEntityFactory $schemeFactory,
        IKnockoutGroupEntityFactory $factory,
        MatchMapper $matchMapper,
        TeamTableMapper $tableMapper)
    {
        $this->roundEntityFactory = $roundEntityFactory;
        $this->schemeFactory = $schemeFactory;
        $this->factory = $factory;
        $this->matchMapper = $matchMapper;
        $this->tableMapper = $tableMapper;
    }


    public function map(IStageEntity $stage, array $knockoutSchemes): array
    {
        $knockoutSchemeEntities = [];
        foreach ($knockoutSchemes as $knockoutScheme) {
            $knockoutRoundEntities = [];
            foreach ($knockoutScheme['rounds'] as $round) {
                $knockoutGroupEntities = array_map(fn(array $group) => $this->factory->setEmpty()
                    ->setId($group['group_id'])
                    ->setOrder($group['group_order'])
                    ->setTeams(array_map([$this->tableMapper, 'toEntity'], $group['teams']))
                    ->setMatches(array_map([$this->matchMapper, 'map'], $group['matches']))
                    ->setChildObjectId($group['child_object_id'])
                    ->create(), $round['groups']);

                $knockoutRoundEntities[] = $this->roundEntityFactory->setEmpty()
                    ->setName($round['name'])
                    ->setGroups($knockoutGroupEntities)
                    ->create();
            }

            $knockoutSchemeEntities[] = $this->schemeFactory->setEmpty()
                ->setStartRound(new KnockoutEdgeRoundEntity($knockoutScheme['start_round']))
                ->setEndRound(new KnockoutEdgeRoundEntity($knockoutScheme['end_round']))
                ->setSmallFinal( !isset($knockoutScheme['small_final']) ? null : (bool) $knockoutScheme['small_final'])
                ->setStage($stage)
                ->setRounds($knockoutRoundEntities)
                ->create();
        }

        return $knockoutSchemeEntities;
    }
}