<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Asset\AssetManager;
use Sportal\FootballApi\Domain\City\ICityEntityFactory;
use Sportal\FootballApi\Domain\City\ICityRepository;
use Sportal\FootballApi\Domain\Translation\ITranslationRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Repository\CityRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\VenueRepository;
use Sportal\FootballFeedCommon\FeedContainer;

class VenueImporter extends Importer
{

    /**
     *
     * @var VenueRepository
     */
    protected $repository;

    protected $assetManager;

    protected $countryImporter;

    protected ICityRepository $cityRepository;

    protected ICityEntityFactory $cityFactory;

    protected ITranslationRepository $translationRepository;

    /**
     *
     * @var Sportal\FootballFeedCommon\VenueFeedInterface[]
     */
    protected $feeds;

    function __construct(VenueRepository $repository,
                         MappingRepositoryContainer $mappings,
                         LoggerInterface $logger,
                         AssetManager $assetManager,
                         CountryImporter $countryImporter,
                         FeedContainer $feeds,
                         ICityRepository $cityRepository,
                         ICityEntityFactory $cityFactory,
                         ITranslationRepository $translationRepository)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->assetManager = $assetManager;
        $this->countryImporter = $countryImporter;
        $this->feeds = $feeds;
        $this->cityRepository = $cityRepository;
        $this->cityFactory = $cityFactory;
        $this->translationRepository = $translationRepository;
    }

    public function import($id, $sourceName = null)
    {
        $data = $this->feeds[$sourceName]->getVenue($id);
        if ($data !== null && !empty($data)) {
            return $this->importData($data, $sourceName, null);
        }
        return null;
    }

    public function importData(array $venueData, $sourceName, $existingId)
    {
        $venueData['country'] = $this->countryImporter->importCountry($venueData['country']);

        if (isset($venueData['city'])) {
            $cityObject = $this->cityRepository->findByUniqueConstraint($venueData['city'], $venueData['country']->getId());
            if ($cityObject == null) {
                $cityObject = $this->cityRepository->insert($this->cityFactory->setEmpty()
                    ->setCountryId($venueData['country']->getId())
                    ->setName($venueData['city'])
                    ->create());
                $translationKey = new TranslationKey(TranslationEntity::CITY(), $cityObject->getId(), 'en');
                $translationContent = new TranslationContent($cityObject->getName());
                $updatedAt = new \DateTime();
                $this->translationRepository->create(new Translation($translationKey, $translationContent, $updatedAt));
            }
            $venueData['city_id'] = $cityObject->getId();
            $venueData['city'] = $cityObject->getName();
        }

        $venue = $this->repository->createObject($venueData);

        $className = $this->repository->getModelClass();
        if ($existingId !== null) {
            $feedId = $this->mappings->get($sourceName)->getRemoteId($className, $existingId);
            if ($feedId === null) {
                $this->mappings->get($sourceName)->setMapping($className, $venueData['id'], $existingId);
            } else {
                $venueData['id'] = $feedId;
            }
        }

        $venue = $this->importMerge($venue, $venueData['id'], $sourceName);
        if ($venue !== null && isset($venueData['assets'])) {
            $this->importImages($venueData['assets'], $venue, $this->assetManager);
        }

        return $venue;
    }
}