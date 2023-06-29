<?php


namespace Sportal\FootballApi\Application\Venue;


use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Application\Venue\Dto\VenueDto;
use Sportal\FootballApi\Application\Venue\Dto\VenueEditDto;
use Sportal\FootballApi\Application\Venue\Dto\VenuePageDto;
use Sportal\FootballApi\Application\Venue\Mapper\VenueEditDtoToFactoryMapper;
use Sportal\FootballApi\Application\Venue\Mapper\VenueEntityToDtoMapper;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\City\ICityRepository;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Infrastructure\Venue\VenueEntity;
use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;

class VenueService
{
    private IVenueRepository $venueRepository;
    private ICountryRepository $countryRepository;
    private TranslationService $translationService;
    private BlacklistService $blacklistService;
    private VenueEditDtoToFactoryMapper $inputMapper;
    private VenueEntityToDtoMapper $outputMapper;
    private IVenueEntityFactory $venueEntityFactory;
    private ICityRepository $cityRepository;

    /**
     * VenueService constructor.
     * @param IVenueRepository $venueRepository
     * @param ICountryRepository $countryRepository
     * @param TranslationService $translationService
     * @param BlacklistService $blacklistService
     * @param VenueEditDtoToFactoryMapper $inputMapper
     * @param VenueEntityToDtoMapper $outputMapper
     * @param IVenueEntityFactory $venueEntityFactory
     * @param ICityRepository $cityRepository
     */
    public function __construct(
        IVenueRepository $venueRepository,
        ICountryRepository $countryRepository,
        TranslationService $translationService,
        BlacklistService $blacklistService,
        VenueEditDtoToFactoryMapper $inputMapper,
        VenueEntityToDtoMapper $outputMapper,
        IVenueEntityFactory $venueEntityFactory,
        ICityRepository $cityRepository
    ) {
        $this->venueRepository = $venueRepository;
        $this->countryRepository = $countryRepository;
        $this->translationService = $translationService;
        $this->blacklistService = $blacklistService;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
        $this->venueEntityFactory = $venueEntityFactory;
        $this->cityRepository = $cityRepository;
    }


    /**
     * @AttachEventNotification(object=EventNotificationEntityType::VENUE,operation=EventNotificationOperationType::UPDATE)
     * @param string $id
     * @param VenueEditDto $venueDto
     * @return VenueDto
     * @throws DuplicateKeyException
     * @throws NoSuchEntityException
     */
    public function update(string $id, VenueEditDto $venueDto)
    {
        if (!$this->venueRepository->exists($id)) {
            throw new NoSuchEntityException('venue_id ' . $id);
        }

        /** @var VenueEntity|null $duplicatedVenueEntity */
        $duplicatedVenueEntity = $this->venueRepository->findByUniqueConstraint(
            $venueDto->getName(), $venueDto->getCountryId(), $venueDto->getCityId()
        );
        if (!is_null($duplicatedVenueEntity) && $duplicatedVenueEntity->getId() != $id) {
            throw new DuplicateKeyException('Duplicate key (name, country_id, city_id).');
        }

        $country = $this->countryRepository->findById($venueDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException('country_id ' . $venueDto->getCountryId());
        }

        $venueEntityFactory = $this->venueEntityFactory->setEntity($this->inputMapper->map($venueDto))
            ->setCountry($country)
            ->setId($id);

        if (!is_null($venueDto->getCityId())) {
            $city = $this->cityRepository->findById($venueDto->getCityId());
            if (is_null($city)) {
                throw new NoSuchEntityException('city_id ' . $venueDto->getCityId());
            }

            $venueEntityFactory->setCity($city);
        }

        $venueEntity = $venueEntityFactory->create();

        $this->venueRepository->update($venueEntity);

        $translationEntity = $this->createTranslation($venueEntity->getId(), $venueEntity->getName());
        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::VENUE(), $venueEntity->getId());
        $this->blacklistService->insertNew([$blacklistKey]);

        return $this->outputMapper->map($venueEntity);
    }

    private function createTranslation(string $id, string $name)
    {
        $translationKey = new TranslationKey(TranslationEntity::VENUE(), $id, 'en');
        $translationContent = new TranslationContent($name);
        $updatedAt = new \DateTime();
        return new Translation($translationKey, $translationContent, $updatedAt);
    }

    /**
     * @AttachEventNotification(object=EventNotificationEntityType::VENUE,operation=EventNotificationOperationType::CREATE)
     * @param VenueEditDto $venueDto
     * @return VenueDto
     * @throws DuplicateKeyException
     * @throws NoSuchEntityException
     */
    public function insert(VenueEditDto $venueDto): VenueDto
    {
        $country = $this->countryRepository->findById($venueDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException('country_id ' . $venueDto->getCountryId());
        }

        $city = null;
        if (!is_null($venueDto->getCityId())) {
            $city = $this->cityRepository->findById($venueDto->getCityId());
            if (is_null($city)) {
                throw new NoSuchEntityException('city_id ' . $venueDto->getCityId());
            }
        }


        $duplicatedVenueEntity = $this->venueRepository->findByUniqueConstraint(
            $venueDto->getName(), $venueDto->getCountryId(), $venueDto->getCityId()
        );
        if (!is_null($duplicatedVenueEntity)) {
            throw new DuplicateKeyException('Duplicate key (name, country_id, city_id).');
        }

        $venueEntity = $this->venueRepository->insert(
            $this->venueEntityFactory
                ->setEntity($this->inputMapper->map($venueDto))
                ->setCountry($country)
                ->setCity($city)
                ->create()
        );

        $translationEntity = $this->createTranslation($venueEntity->getId(), $venueEntity->getName());
        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::VENUE(), $venueEntity->getId());
        $this->blacklistService->insertNew([$blacklistKey]);

        return $this->outputMapper->map($venueEntity);
    }

    public function findAll(): VenuePageDto
    {
        $venues = $this->venueRepository->findAll();
        $venueDtoArray = array_map([$this->outputMapper, 'map'], $venues);
        return new VenuePageDto($venueDtoArray);
    }


    /**
     * @param int $id
     * @return VenueDto
     * @throws NoSuchEntityException
     */
    public function findById(int $id): VenueDto
    {
        $venueEntity = $this->venueRepository->findById($id);
        if ($venueEntity === null) {
            throw new NoSuchEntityException('venue ' . $id);
        }
        return $this->outputMapper->map($venueEntity);
    }
}