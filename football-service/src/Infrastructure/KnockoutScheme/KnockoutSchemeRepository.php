<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeRepository;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Infrastructure\Repository\EnetpulseMappingRepository;
use Sportal\FootballFeedCommon\KnockoutScheme\KnockoutSchemeInterface;

class KnockoutSchemeRepository implements IKnockoutSchemeRepository
{

    private EnetpulseMappingRepository $enetpulseMappingRepository;

    private KnockoutSchemeInterface $knockoutScheme;

    private Mapper $mapper;

    private KnockoutSchemeIdMapper $idMapper;

    /**
     * KnockoutSchemeRepository constructor.
     * @param EnetpulseMappingRepository $enetpulseMappingRepository
     * @param KnockoutSchemeInterface $knockoutScheme
     * @param Mapper $mapper
     * @param KnockoutSchemeIdMapper $idMapper
     */
    public function __construct(
        EnetpulseMappingRepository $enetpulseMappingRepository,
        KnockoutSchemeInterface $knockoutScheme,
        Mapper $mapper,
        KnockoutSchemeIdMapper $idMapper)
    {
        $this->enetpulseMappingRepository = $enetpulseMappingRepository;
        $this->knockoutScheme = $knockoutScheme;
        $this->mapper = $mapper;
        $this->idMapper = $idMapper;
    }

    public function findByStage(IStageEntity $stage): array
    {
        $mapping = $this->enetpulseMappingRepository->getMappingFromFeed(EntityType::STAGE()->getValue(), $stage->getId());
        if (isset($mapping[$stage->getId()])) {
            $knockoutSchemeEntities = $this->mapper->map($stage, $this->knockoutScheme->getKnockoutScheme($mapping[$stage->getId()]));
            return $this->idMapper->map($knockoutSchemeEntities);
        } else {
            return [];
        }

    }
}