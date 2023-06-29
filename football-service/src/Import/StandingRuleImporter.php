<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\StandingRuleRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Psr\Log\LoggerInterface;

class StandingRuleImporter extends Importer
{

    public function __construct(StandingRuleRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger)
    {
        parent::__construct($repository, $mappings, $logger);
    }

    /**
     *
     * @var StandingRuleRepository
     */
    protected $repository;

    public function import(array $rule)
    {
        $model = $this->repository->createObject($rule);
        return $this->importMatching($model,
            function ($matching) {
                return $this->repository->findExisting($matching);
            });
    }
}