<?php


namespace Sportal\FootballApi\Asset;


use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Infrastructure\Asset\AssetEntityFilter;
use Sportal\FootballApi\Infrastructure\Asset\AssetRepository;
use Sportal\FootballApi\Infrastructure\Mapper\RecursiveMapper;

class AssetResolver
{
    private AssetRepository $assetRepository;

    private RecursiveMapper $mapper;


    /**
     * AssetResolver constructor.
     * @param AssetRepository $assetRepository
     * @param RecursiveMapper $mapper
     */
    public function __construct(AssetRepository $assetRepository,
                                RecursiveMapper $mapper)
    {
        $this->assetRepository = $assetRepository;
        $this->mapper = $mapper;
    }

    public function resolve($data): void
    {
        $objectMap = [];
        $assetEntityFilters = $this->mapper->map($data,
            fn($data) => $data instanceof Assetable && AssetEntityType::isValid($data->getAssetModelName()),
            function (Assetable $object) use (&$objectMap) {
                $filter = new AssetEntityFilter(new AssetEntityType($object->getAssetModelName()), $object->getId());
                $objectMap[(string)$filter][] = $object;
                return $filter;
            });
        $allAssets = $this->assetRepository->findByEntities($assetEntityFilters);
        foreach ($allAssets as $asset) {
            $filter = new AssetEntityFilter($asset->getEntity(), $asset->getEntityId());
            $objects = $objectMap[(string)$filter] ?? [];
            foreach ($objects as $object) {
                /**
                 * @var Assetable $object
                 */
                $assetType = $asset->getType()->getValue();
                if ($object->isSupported($assetType)) {
                    $object->setAssetFilename($assetType, $asset->getPath());
                }
            }
        }
    }
}