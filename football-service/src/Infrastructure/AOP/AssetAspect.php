<?php
namespace Sportal\FootballApi\Infrastructure\AOP;

use App\Asset\AssetURLBuilder;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\AssetResolver;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Infrastructure\Asset\AssetEntityFilter;
use Sportal\FootballApi\Infrastructure\Asset\AssetRepository;
use Sportal\FootballApi\Infrastructure\Mapper\RecursiveMapper;

class AssetAspect implements Aspect
{
    /**
     * Attaches assets to objects returned by IService process method
     * @Around("@execution(Sportal\FootballApi\Infrastructure\AOP\AttachAssets)")
     * @param MethodInvocation $invocation Invocation
     * @return mixed
     * @see IService
     */
    public function aroundServiceMethod(MethodInvocation $invocation)
    {
        $data = $invocation->proceed();
        $mapper = app()->get(RecursiveMapper::class);
        $assetRepository = app()->get(AssetRepository::class);
        $assetBuilder = app()->get(AssetURLBuilder::class);

        $objectMap = [];
        $assetEntityFilters = $mapper->map($data,
            fn($data) => $data instanceof IAssetable && !is_null($data->getId()),
            function (IAssetable $object) use (&$objectMap) {
                $filter = new AssetEntityFilter($object->getEntityType(), $object->getId());
                $objectMap[(string)$filter][] = $object;
                return $filter;
            }
        );

        $allAssets = $assetRepository->findByEntities($assetEntityFilters);

        foreach ($allAssets as $asset) {
            $filter = new AssetEntityFilter($asset->getEntity(), $asset->getEntityId());
            $objects = $objectMap[(string)$filter] ?? [];
            foreach ($objects as $object) {
                /**
                 * @var IAssetable $object
                 */
                $assetType = $asset->getType();
                $object->setAssets($assetType, ['url' => $assetBuilder->getBaseUrl() . '/' . $asset->getPath()]);
            }
        }

        return $data;
    }
}