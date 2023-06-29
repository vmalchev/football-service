<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Asset\AssetManager;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\TeamRepository;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballFeedCommon\TeamFeedInterface;

class TeamImporter extends Importer
{

    /**
     *
     * @var TeamRepository
     */
    protected $repository;

    /**
     *
     * @var CountryImporter
     */
    private $countryImporter;

    /**
     *
     * @var TeamFeedInterface[]
     */
    private $feeds;

    /**
     *
     * @var JobDispatcherInterface
     */
    private $dispatcher;

    private $assetManager;

    private $venueImporter;

    public function __construct(TeamRepository $repository, MappingRepositoryContainer $mappings,
                                LoggerInterface $logger, FeedContainer $feeds, CountryImporter $countryImporter,
                                JobDispatcherInterface $dispatcher, AssetManager $assetManager, VenueImporter $venueImporter)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->feeds = $feeds;
        $this->countryImporter = $countryImporter;
        $this->dispatcher = $dispatcher;
        $this->assetManager = $assetManager;
        $this->venueImporter = $venueImporter;
    }

    protected function dispatchTasks($team, $teamArr)
    {
        $this->dispatcher->dispatch('Import\MlContent', [
            $team,
            $teamArr['id']
        ]);
        $this->dispatcher->dispatch('Import\TeamPlayer', [
            $team->getId()
        ]);
        $this->dispatcher->dispatch('Import\TeamCoach', [
            $team->getId()
        ]);
    }

    protected function importVenue($team, $feedId, $sourceName = null)
    {
        $venueData = $this->feeds[$sourceName]->getVenue($feedId);
        if (!empty($venueData)) {
            $venue = $this->venueImporter->importData($venueData, $sourceName, null);
            $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::TEAM(), $team->getId());
            if ($venue !== null &&
                !$this->blacklistRepository->exists($blacklistKey)) {
                $team->setVenue($venue);
                $this->repository->update($team);
            }
        }
    }

    /**
     *
     * @param unknown $feedId
     * @param unknown $sourceName
     * @return \Sportal\FootballApi\Model\Team
     */
    public function import($feedId, $sourceName = null)
    {
        $teamArr = $this->feeds[$sourceName]->getTeam($feedId);
        if ($teamArr !== null) {
            $teamArr['country'] = $this->countryImporter->importCountry($teamArr['country']);
            $team = $this->repository->createObject($teamArr);
            $team = $this->importMerge($team, $feedId, $sourceName,
                $this->feeds->createAllowed($this->feeds[$sourceName]),
                function ($team, $changes) use ($teamArr) {
                    if (count($changes) === 0) {
                        $this->dispatchTasks($team, $teamArr);
                    }
                });

            if ($team !== null) {
                $this->importVenue($team, $teamArr['id'], $sourceName);
                if (isset($teamArr['assets']) && $team !== null) {
                    $this->importImages($teamArr['assets'], $team, $this->assetManager);
                }
            }

            $this->handleChanges();
            return $team;
        }
        return null;
    }

    public function getOrImport($feedId, $partial = false, $sourceName = null)
    {
        $mapping = ($sourceName !== null) ? $this->mappings->get($sourceName) : $this->mappings->getDefault();
        $ownTeamId = $mapping->getOwnId($this->repository->getModelClass(), $feedId);
        if ($ownTeamId === null || ($team = $this->repository->find($ownTeamId)) === null) {
            $team = $this->import($feedId, $sourceName);
        }
        return ($partial && $team !== null) ? $this->repository->clonePartial($team) : $team;
    }
}