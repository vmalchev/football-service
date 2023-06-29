<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Asset\AssetManager;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\PlayerRepository;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballFeedCommon\PlayerFeedInterface;

class PlayerImporter extends PersonImporter
{

    /**
     *
     * @var PlayerFeedInterface[]
     */
    private $feeds;

    public function __construct(PlayerRepository $repository, MappingRepositoryContainer $mappings,
                                LoggerInterface $logger, CountryImporter $countryImporter, JobDispatcherInterface $dispatcher,
                                AssetManager $assetManager, FeedContainer $feeds)
    {
        parent::__construct($repository, $mappings, $logger, $countryImporter, $dispatcher, $assetManager);
        $this->feeds = $feeds;
    }

    public function import($feedId, $sourceName = null)
    {
        $feed = ($sourceName !== null) ? $this->feeds[$sourceName] : $this->feeds->getDefault();
        $playerArr = $feed->getPlayer($feedId);

        if ($playerArr !== null) {
            $player = $this->importData($playerArr, $feedId, $sourceName, $this->feeds->createAllowed($feed));
            if ($player !== null && isset($playerArr['assets'])) {
                $this->importImages($playerArr['assets'], $player, $this->assetManager);
            }
            return $player;
        }
        return null;
    }
}