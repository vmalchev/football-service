<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballApi\Repository\Repository;
use Sportal\FootballApi\Util\NameUtil;

abstract class MappableImporter
{

    /**
     *
     * @var Repository
     */
    protected $repository;

    /**
     *
     * @var MappingRepositoryInterface
     */
    protected $mapping;

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

    public function __construct(Repository $repository, MappingRepositoryInterface $mapping, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->mapping = $mapping;
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
    public function importModel(ModelInterface $model, $feedId, callable $onUpdate = null, $matchExisting = false)
    {
        $className = get_class($model);
        if (($existing = $this->findExistingModel($model, $feedId, $matchExisting)) === null) {
            $this->createModel($model, $feedId);
            $createdId = ($model instanceof SurrogateKeyInterface) ? $model->getId() : $model->getPrimaryKeyMap();
            $this->mapping->setMapping($className, $feedId, $createdId);
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

    public function getSourceName()
    {
        return $this->mapping->getSourceName();
    }

    protected function findExistingModel($model, $feedId, $checkExisting)
    {
        $ownId = $this->mapping->getOwnId($this->repository->getModelClass(), $feedId);
        if ($ownId !== null && ($existing = $this->repository->find($ownId)) !== null) {
            return $existing;
        } elseif ($checkExisting && method_exists($this->repository, 'findExisting')) {
            $existing = $this->repository->findExisting($model);
            if ($existing !== null) {
                $this->mapping->setMapping($this->repository->getModelClass(), $feedId, $existing->getId());
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
        $changes = $this->repository->getChangedKeys($existing, $updated);
        if (count($changes) > 0 && !$this->isBlacklisted($existing)) {
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

    private function isBlacklisted(ModelInterface $existing): bool
    {
        if (is_null($this->blacklistRepository)) {
            throw new \Exception('No Blacklist Repository instance found');
        }

        $isBlacklisted = false;
        $entityName = NameUtil::persistanceName(get_class($existing));
        if ($entityName == 'event') {
            $entityName = 'match';
        }
        if (in_array($entityName, BlacklistEntityName::values())) {
            $blacklistKey = new BlacklistKey(
                BlacklistType::ENTITY(),
                new BlacklistEntityName($entityName),
                $existing->getPrimaryKeyMap()['id']
            );
            $isBlacklisted = $this->blacklistRepository->exists($blacklistKey);
        }
        return $isBlacklisted;
    }
}