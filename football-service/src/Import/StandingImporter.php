<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Model\Standing;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Model\TournamentSeasonStage;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\StageGroupRepository;
use Sportal\FootballApi\Repository\StandingDataRepository;
use Sportal\FootballApi\Repository\StandingRepository;
use Sportal\FootballFeedCommon\StandingFeedInterface;

class StandingImporter extends Importer
{

    /**
     *
     * @var StandingDataRepository
     */
    protected $repository;

    /**
     *
     * @var StandingRepository
     */
    protected $standingRepository;

    /**
     *
     * @var TeamImporter
     */
    protected $teamImporter;

    /**
     *
     * @var PlayerImporter
     */
    protected $playerImporter;

    /**
     *
     * @var StandingFeedInterface
     */
    protected $feed;

    protected $ruleImporter;

    protected $dispatcher;

    protected $stageGroupRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    public function __construct(StandingDataRepository $repository, MappingRepositoryContainer $mappings,
                                LoggerInterface $logger, StandingRepository $standingRepository, TeamImporter $teamImporter,
                                PlayerImporter $playerImporter, StandingFeedInterface $feed, StandingDataRuleImporter $ruleImporter,
                                StageGroupRepository $stageGroupRepository, JobDispatcherInterface $dispatcher, IBlacklistKeyFactory $blacklistKeyFactory)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->standingRepository = $standingRepository;
        $this->teamImporter = $teamImporter;
        $this->playerImporter = $playerImporter;
        $this->feed = $feed;
        $this->ruleImporter = $ruleImporter;
        $this->dispatcher = $dispatcher;
        $this->stageGroupRepository = $stageGroupRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
    }

    protected function importStandingData(SurrogateKeyInterface $model, array $data, $type)
    {
        if (count($data) > 0) {
            $standing = $this->standingRepository->findByModelType($model, $type);
            $existingData = [];
            if ($standing === null) {
                $standing = $this->standingRepository->createObject(
                    [
                        'type' => $type,
                        'model' => $model
                    ]);
                $this->standingRepository->create($standing);
                $this->standingRepository->refreshCache([], [
                    $standing
                ]);
            } else {
                $existingData = $this->repository->findByStandingId($standing->getId());
            }
            if ($this->hasBlacklist($standing, $type)) {
                return $standing;
            }
            $updated = [];
            foreach ($data as $standingData) {
                $standingData['standing_id'] = $standing->getId();
                if (isset($standingData['player_id'])) {
                    $standingData['player'] = $this->playerImporter->getOrImport($standingData['player_id'], true);
                }
                if (empty($standingData['team_id'])) {
                    continue;
                }
                $standingData['team'] = $this->teamImporter->getOrImport($standingData['team_id'], true);
                $updated[] = $this->repository->createObject($standingData);
            }
            $this->importMatchableList($existingData, $updated);
            $this->handleChanges();
            return $standing;
        }
    }

    public function importStandings(SurrogateKeyInterface $league, $sourceName = null)
    {
        $id = $this->mappings->get($sourceName)->getRemoteId(get_class($league), $league->getId());
        $availableTypes = $this->feed->getAvailable(get_class($league), $id);
        if (in_array(Standing::TYPE_LEAGUE_LIVE, $availableTypes)) {
            $this->importLeagueStanding($league, true);
        } elseif (in_array(Standing::TYPE_LEAGUE, $availableTypes)) {
            $this->importLeagueStanding($league, false);
        }

        if (in_array(Standing::TYPE_TOPSCORER, $availableTypes)) {
            $this->importTopScorer($league);
        }
        if (in_array(Standing::TYPE_CARDLIST, $availableTypes)) {
            $this->importCardList($league);
        }
    }

    public function importTopScorer(SurrogateKeyInterface $league, $sourceName = null)
    {
        $id = $this->mappings->get($sourceName)->getRemoteId(get_class($league), $league->getId());
        $data = $this->feed->getTopScorer(get_class($league), $id);
        $this->importStandingData($league, $data, Standing::TYPE_TOPSCORER);
    }

    public function importCardList(SurrogateKeyInterface $league, $sourceName = null)
    {
        $id = $this->mappings->get($sourceName)->getRemoteId(get_class($league), $league->getId());
        $data = $this->feed->getCardList(get_class($league), $id);
        $this->importStandingData($league, $data, Standing::TYPE_CARDLIST);
    }

    public function importLeagueStanding(SurrogateKeyInterface $league, $live = false, $sourceName = null)
    {
        $id = $this->mappings->get($sourceName)->getRemoteId(get_class($league), $league->getId());
        list ($standing, $rules) = $this->feed->getLeagueStanding(get_class($league), $id, $live);
        $standing = $this->importStandingData($league, $standing, Standing::TYPE_LEAGUE);
        if ($standing !== null && !$this->hasBlacklist($standing, 'league_standing_rules')) {
            $this->ruleImporter->importRules($standing->getId(), $rules);
        }
    }

    public function importStage(TournamentSeasonStage $stage, $sourceName = null)
    {
        if ($stage->hasGroups()) {
            foreach ($this->stageGroupRepository->findByStage($stage->getId()) as $group) {
                $this->importStandings($group, $sourceName);
            }
        } else {
            $this->importStandings($stage);
        }
    }

    private function hasBlacklist(Standing $standing, string $context): bool
    {
        $key = $this->blacklistKeyFactory->setEmpty()->setType(BlacklistType::RELATION())
            ->setEntity(new BlacklistEntityName($standing->getEntity()))
            ->setEntityId($standing->getEntityId())
            ->setContext($context)
            ->create();
        return $this->blacklistRepository->exists($key);
    }
}