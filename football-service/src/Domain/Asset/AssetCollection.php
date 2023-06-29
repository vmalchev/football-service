<?php


namespace Sportal\FootballApi\Domain\Asset;


use Sportal\FootballApi\Application\Exception\NoSuchAssetException;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;

final class AssetCollection implements IAssetCollection
{
    /**
     * IAssetBuilder
     */
    private IAssetBuilder $assetBuilder;

    /**
     * @var IAssetRepository
     */
    private IAssetRepository $assetRepository;

    /**
     * @var IAssetModel[]
     */
    private array $assetModels;

    /**
     * @var IBlacklistKey[]
     */
    private array $blacklistKeys;

    private IBlacklistRepository $blacklistRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    private IAssetEntityFactory $assetFactory;

    public function __construct(IAssetBuilder $assetBuilder,
                                IAssetRepository $assetRepository,
                                IBlacklistRepository $blacklistRepository,
                                IBlacklistKeyFactory $blacklistKeyFactory,
                                IAssetEntityFactory $assetFactory)
    {
        $this->assetBuilder = $assetBuilder;
        $this->assetRepository = $assetRepository;
        $this->blacklistRepository = $blacklistRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
        $this->assetFactory = $assetFactory;
        $this->assetModels = [];
        $this->blacklistKeys = [];
    }


    /**
     * @param IAssetEntity[] $assetEntities
     * @return IAssetCollection
     * @throws \Exception
     */
    public function upsert(array $assetEntities): IAssetCollection
    {
        $assetCollection = clone $this;

        foreach ($assetEntities as $assetEntity) {
            $dbAsset = $this->assetRepository->find($assetEntity);

            if ($dbAsset !== null) {
                $assetModel = $this->assetBuilder
                    ->build(
                        $this->assetFactory
                            ->setAssetEntity($assetEntity)
                            ->setId($dbAsset->getId())
                            ->create()
                    )->update();

                $assetCollection->add($assetModel);
            } else {
                $assetModel = $this->assetBuilder->build($assetEntity)->create();
                $assetCollection->add($assetModel);
            }
        }

        return $assetCollection;
    }

    public function withBlacklist(): IAssetCollection
    {
        if (!empty($this->assetModels)) {
            foreach ($this->assetModels as $assetModel) {
                $asset = $this->assetRepository->find($assetModel->getEntity());

                $blacklistKey = $this->blacklistKeyFactory->setEmpty()
                    ->setType(BlacklistType::ENTITY())
                    ->setEntity(BlacklistEntityName::ASSET())
                    ->setEntityId($asset->getId())
                    ->create();

                $this->blacklistRepository->upsert($blacklistKey);
            }
        }

        return $this;
    }

    public function removeBlacklist(): IAssetCollection
    {
        if (!empty($this->blacklistKeys)) {
            $this->blacklistRepository->deleteAll($this->blacklistKeys);
        }

        return $this;
    }

    /**
     * @param IAssetEntity[] $assetEntities
     * @return $this|IAssetCollection
     * @throws \Exception
     */
    public function delete(array $assetEntities): IAssetCollection
    {
        foreach ($assetEntities as $assetEntity) {
            $asset = $this->assetRepository->find($assetEntity);

            if (is_null($asset)) {
                $assetData = [
                    'entity' => $assetEntity->getEntity()->getKey(),
                    'entity_id' => $assetEntity->getEntityId(),
                    'type' => $assetEntity->getType()->getKey(),
                ];

                if (!is_null($assetEntity->getContextType()) && !is_null($assetEntity->getContextId())) {
                    $assetData['context_type'] = $assetEntity->getContextType()->getKey();
                    $assetData['context_id'] = $assetEntity->getContextId();
                }

                array_walk($assetData, fn(&$value, $item) => $value = $item . ': ' . $value , ', ');

                throw new NoSuchAssetException("No such asset: " . implode( ' ,', $assetData));
            }

            $this->assetBuilder->build($asset)->delete();

            $this->blacklistKeys[]= $this->blacklistKeyFactory->setEmpty()
                ->setType(BlacklistType::ENTITY())
                ->setEntity(BlacklistEntityName::ASSET())
                ->setEntityId($asset->getId())
                ->create();
        }

        return $this;
    }

    /**
     * @param IAssetModel $assetModel
     */
    public function add(IAssetModel $assetModel)
    {
        $this->assetModels[] = $assetModel;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->assetModels);
    }
}