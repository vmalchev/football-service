<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\AssetManager;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Model\Comparable;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\Repository;
use Sportal\FootballApi\Util\NameUtil;

abstract class Importer
{

    /**
     *
     * @var Repository
     */
    protected $repository;

    /**
     *
     * @var MappingRepositoryContainer
     */
    protected $mappings;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var array
     */
    protected $changedKeys;

    /**
     *
     * @var array
     */
    protected $createdModels;

    /**
     * @var \Sportal\FootballApi\Infrastructure\Blacklist\BlacklistRepository
     */
    protected $blacklistRepository;

    public function __construct(Repository $repository, MappingRepositoryContainer $mappings, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->mappings = $mappings;
        $this->logger = $logger;
        $this->changedKeys = [];
        $this->createdModels = [];

        $this->blacklistRepository = BlacklistRepositoryFactory::getInstance();
    }

    /**
     *
     * @param ModelInterface $model
     * @param mixed $feedId
     * @return ModelInterface Updated/Created model as it is currently saved in persistance
     */
    public function importModel(SurrogateKeyInterface $model, $feedId, $sourceName = null, callable $onUpdate = null)
    {
        $className = get_class($model);
        if (($existing = $this->findExistingModel($model, $feedId, false, $sourceName)) === null) {
            $this->createModel($model, $feedId);
            $mapping = ($sourceName !== null) ? $this->mappings->get($sourceName) : $this->mappings->getDefault();
            $mapping->setMapping($className, $feedId, $model->getId());
            $changes = [];
        } else {
            list ($model, $changes) = $this->updateModel($existing, $model);
        }
        $this->notify($model, $changes, $onUpdate);
        return $model;
    }

    public function importMatching(ModelInterface $model, callable $matcher, callable $onUpdate = null)
    {
        $existing = $matcher($model);
        if ($existing === null) {
            $this->createModel($model);
            $changes = [];
        } else {
            list ($model, $changes) = $this->updateModel($existing, $model);
        }
        $this->notify($model, $changes, $onUpdate);
        return $model;
    }

    /**
     *
     * @param SurrogateKeyInterface $model
     * @param mixed $feedId
     * @param string $sourceName
     * @param boolean $allowCreate
     * @param callable $onUpdate
     * @return SurrogateKeyInterface
     */
    public function importMerge(SurrogateKeyInterface $model, $feedId, $sourceName, $allowCreate = true,
                                callable $onUpdate = null)
    {
        $className = get_class($model);
        $existing = $this->findExistingModel($model, $feedId, true, $sourceName);
        if ($existing !== null) {
            list ($model, $changes) = $this->updateModel($existing, $model);
            $this->notify($model, $changes, $onUpdate);
            return $model;
        } elseif ($allowCreate) {
            $this->createModel($model, $feedId);
            $this->mappings->get($sourceName)->setMapping($className, $feedId, $model->getId());
            $this->notify($model, [], $onUpdate);
            return $model;
        } else {
            return null;
        }
    }

    public function handleChanges(callable $handler = null)
    {
        if (count($this->changedKeys) > 0 || count($this->createdModels) > 0) {
            if ($handler !== null) {
                $handler($this->changedKeys, $this->createdModels);
            } else {
                $this->repository->refreshCache($this->changedKeys, $this->createdModels);
            }

            $this->changedKeys = [];
            $this->createdModels = [];
        }
    }

    protected function findExistingModel(SurrogateKeyInterface $model, $feedId, $searchExisting, $sourceName = null)
    {
        $className = $this->repository->getModelClass();
        $mapping = ($sourceName !== null) ? $this->mappings->get($sourceName) : $this->mappings->getDefault();
        $ownId = $mapping->getOwnId($className, $feedId);
        if ($ownId !== null && ($existing = $this->repository->find($ownId)) !== null) {
            return $existing;
        } elseif ($searchExisting && method_exists($this->repository, 'findExisting')) {
            $existing = $this->repository->findExisting($model);
            if ($existing !== null && $mapping->getRemoteId($className, $existing->getId()) === null) {
                $mapping->setMapping($className, $feedId, $existing->getId());
                return $existing;
            }
        }
        return null;
    }

    private function createModel(ModelInterface $model, $feedId = null)
    {
        $this->repository->create($model);
        $this->logger->info(
            NameUtil::shortClassName(get_class($this)) . ": Created " . NameUtil::shortClassName(get_class($model)) . " " .
            implode(',', $model->getPrimaryKeyMap()) .
            ($feedId !== null && is_scalar($feedId) ? ' -> ' . $feedId : ''));
        $this->createdModels[] = $model;
    }

    private function notify(ModelInterface $model, $changes, callable $onUpdate = null)
    {
        if ($onUpdate !== null && $changes !== null) {
            $onUpdate($model, $changes);
        }
    }

    private function updateModel(ModelInterface $existing, ModelInterface $updated)
    {
        if (is_null($this->blacklistRepository)) {
            throw new \Exception('No Blacklist Repository instance found');
        }

        $isBlacklisted = false;
        if (in_array(NameUtil::persistanceName(get_class($existing)), BlacklistEntityName::values())) {
            $blacklistKey = new BlacklistKey(
                BlacklistType::ENTITY(),
                new BlacklistEntityName(NameUtil::persistanceName(get_class($existing))),
                $existing->getPrimaryKeyMap()['id']
            );

            $isBlacklisted = $this->blacklistRepository->exists($blacklistKey);
        }

        $changes = $this->repository->getChangedKeys($existing, $updated);
        if (count($changes) > 0 && $isBlacklisted === false) {
            $model = $this->repository->patchExisting($existing, $updated);
            $this->repository->update($model);
            $this->logger->info(
                NameUtil::shortClassName(get_class($this)) . ": Updated " . NameUtil::shortClassName(get_class($model)) .
                " " . implode(',', $model->getPrimaryKeyMap()) . " Changed: " . implode(",", $changes));
            $this->changedKeys = array_unique(array_merge($this->changedKeys, $changes));
            return [
                $model,
                $changes
            ];
        } else {
            return [
                $existing,
                null
            ];
        }
    }

    /**
     *
     * @param Comparable[] $existingData
     * @param Comparable[] $updated
     */
    public function importMatchableList(array $existingData, array $updated)
    {
        foreach ($updated as $model) {
            $this->importMatching($model,
                function (Comparable $model) use (&$existingData) {
                    foreach ($existingData as $key => $other) {
                        if ($other !== null && $model->equals($other)) {
                            $existingData[$key] = null;
                            return $other;
                        }
                    }
                    return null;
                });
        }

        foreach ($existingData as $model) {
            if ($model !== null) {
                $this->logger->info(
                    NameUtil::shortClassName(get_class($this)) . " Deleting: " . implode(',',
                        $model->getPrimaryKeyMap()));
                $this->repository->delete($model);
            }
        }
    }

    protected function importImages(array $images, Assetable $model, AssetManager $assetManager)
    {
        if ($model !== null) {
            $changes = false;
            foreach ($model->getAssetTypes() as $type) {
                if (isset($images[$type])) {
                    if ($model->hasAsset($type)) {
                        $this->logger->info(
                            'Updating ' . $type . ' ' . NameUtil::shortClassName(get_class($model)) . ":" .
                            $model->getAssetFilename($type));
                    }
                    $assetManager->saveImage($model, $images[$type], $type);
                    $changes = true;
                }
            }
            if ($changes) {
                $this->repository->update($model);
            }
        }
    }
}