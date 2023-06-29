<?php
namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\Repository;
use Sportal\FootballApi\Repository\StandingDataRuleRepository;

class StandingDataRuleImporter extends Importer
{

    /**
     *
     * @var StandingDataRuleRepository
     */
    protected $repository;

    protected $standingRuleImporter;

    public function __construct(StandingDataRuleRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, StandingRuleImporter $standingRuleImporter)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->standingRuleImporter = $standingRuleImporter;
    }

    public function importRules($standingId, array $rules)
    {
        $updated = [];
        foreach ($rules as $ruleArr) {
            $standingRule = $this->standingRuleImporter->import($ruleArr['standing_rule']);
            $model = $this->repository->createObject(
                [
                    'standing_id' => $standingId,
                    'standing_rule' => $standingRule,
                    'rank' => $ruleArr['rank']
                ]);
            $updated[] = $model;
        }
        $existing = $this->repository->findByStanding($standingId);
        $this->importMatchableList($existing, $updated);
        $this->handleChanges();
    }
}