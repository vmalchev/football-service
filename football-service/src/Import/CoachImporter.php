<?php
namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Asset\AssetManager;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballApi\Repository\CoachRepository;
use Sportal\FootballFeedCommon\CoachFeedInterface;

class CoachImporter extends PersonImporter
{

    /**
     *
     * @var CoachFeedInterface[]
     */
    private $feeds;

    public function __construct(CoachRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, CountryImporter $countryImporter, JobDispatcherInterface $dispatcher,
        AssetManager $assetManager, FeedContainer $feeds)
    {
        parent::__construct($repository, $mappings, $logger, $countryImporter, $dispatcher, $assetManager);
        $this->feeds = $feeds;
    }

    public function import($feedId, $sourceName = null)
    {
        $feed = ($sourceName !== null) ? $this->feeds[$sourceName] : $this->feeds->getDefault();
        $personArr = $feed->getCoach($feedId);
        if ($personArr !== null) {
            return $this->importData($personArr, $feedId, $sourceName);
        }
        return null;
    }
}