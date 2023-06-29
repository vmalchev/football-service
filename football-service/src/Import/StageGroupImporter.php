<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\StageGroupRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Psr\Log\LoggerInterface;

class StageGroupImporter extends Importer
{

    /**
     *
     * @var StageGroupRepository
     */
    protected $repository;

    public function __construct(StageGroupRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger)
    {
        parent::__construct($repository, $mappings, $logger);
    }

    public function import(array $group, $sourceName = null)
    {
        $model = $this->repository->createObject($group);
        if (! isset($group['id'])) {
            $model = $this->importMatching($model,
                function ($model) {
                    return $this->repository->findExisting($model);
                });
        } else {
            $model = $this->importMerge($model, $group['id'], $sourceName);
        }
        
        return $model;
    }
}