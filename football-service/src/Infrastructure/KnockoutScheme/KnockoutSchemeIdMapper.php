<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Adapter\EntityMappingCollectionFactory;
use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Adapter\MappingRequest;
use Sportal\FootballApi\Adapter\Provider;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeEntity;

class KnockoutSchemeIdMapper
{
    private EntityMappingCollectionFactory $mappingCollectionFactory;

    /**
     * KnockoutSchemeIdMapper constructor.
     * @param EntityMappingCollectionFactory $mappingCollectionFactory
     */
    public function __construct(EntityMappingCollectionFactory $mappingCollectionFactory)
    {
        $this->mappingCollectionFactory = $mappingCollectionFactory;
    }


    /**
     * @param IKnockoutSchemeEntity[] $knockoutSchemes
     * @return array
     */
    public function map(array $knockoutSchemes): array
    {
        $mappingRequests = [];
        foreach ($knockoutSchemes as $knockoutScheme) {
            foreach ($knockoutScheme->getRounds() as $round) {
                foreach ($round->getGroups() as $group) {
                    foreach ($group->getTeams() as $team) {
                        $mappingRequests[] = new MappingRequest(Provider::ENETPULSE(), EntityType::TEAM(), $team->getId());
                    }
                    foreach ($group->getMatches() as $match) {
                        $mappingRequests[] = new MappingRequest(Provider::ENETPULSE(), EntityType::MATCH(), $match->getId());
                        if ($match->getHomeTeamId() !== null) {
                            $mappingRequests[] = new MappingRequest(Provider::ENETPULSE(), EntityType::TEAM(), $match->getHomeTeamId());
                        }
                        if ($match->getAwayTeamId() !== null) {
                            $mappingRequests[] = new MappingRequest(Provider::ENETPULSE(), EntityType::TEAM(), $match->getAwayTeamId());
                        }
                    }
                }
            }
        }
        $mappingCollection = $this->mappingCollectionFactory->createFromFeed($mappingRequests);
        foreach ($knockoutSchemes as $knockoutScheme) {
            foreach ($knockoutScheme->getRounds() as $round) {
                foreach ($round->getGroups() as $group) {
                    foreach ($group->getTeams() as $team) {
                        $team->setId($mappingCollection->getDomainId(EntityType::TEAM(), $team->getId()));
                    }
                    foreach ($group->getMatches() as $match) {
                        $match->setId($mappingCollection->getDomainId(EntityType::MATCH(), $match->getId()))
                            ->setHomeTeamId($mappingCollection->getDomainId(EntityType::TEAM(), $match->getHomeTeamId()))
                            ->setAwayTeamId($mappingCollection->getDomainId(EntityType::TEAM(), $match->getAwayTeamId()));
                    }
                }
            }
        }
        return $knockoutSchemes;
    }
}