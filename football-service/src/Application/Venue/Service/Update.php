<?php


namespace Sportal\FootballApi\Application\Venue\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Venue\Input;
use Sportal\FootballApi\Application\Venue\Output;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Application\Venue\Mapper\VenueEditDtoToFactoryMapper;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Domain\City\ICityRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


final class Update implements IService
{
    private IVenueRepository $venueRepository;

    private Output\Update\Mapper $outputMapper;

    private IVenueEntityFactory $venueEntityFactory;

    private ICountryRepository $countryRepository;

    private ICityRepository $cityRepository;

    private VenueEditDtoToFactoryMapper $inputMapper;

    private TranslationService $translationService;

    private BlacklistService $blacklistService;


    /**
     * @param IVenueRepository $venueRepository
     * @param VenueEditDtoToFactoryMapper $inputMapper
     * @param BlacklistService $blacklistService
     * @param TranslationService $translationService
     * @param ICountryRepository $countryRepository
     * @param Output\Update\Mapper $outputMapper
     * @param IVenueEntityFactory $venueEntityFactory
     * @param ICityRepository $cityRepository
     */
    public function __construct(
        IVenueRepository $venueRepository,
        ICountryRepository $countryRepository,
        ICityRepository $cityRepository,
        Output\Update\Mapper $outputMapper,
        BlacklistService $blacklistService,
        TranslationService $translationService,
        VenueEditDtoToFactoryMapper $inputMapper,
        IVenueEntityFactory $venueEntityFactory
    ) {
        $this->venueRepository = $venueRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->outputMapper = $outputMapper;
        $this->blacklistService = $blacklistService;
        $this->translationService = $translationService;
        $this->inputMapper = $inputMapper;
        $this->venueEntityFactory = $venueEntityFactory;
    }

    /**
     * @AttachAssets
     * @param Input\Update\Dto $inputDto
     * @return Output\Update\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto)
    {
        if (!$this->venueRepository->exists($inputDto->getId())) {
            throw new NoSuchEntityException('venue_id ' . $inputDto->getId());
        }

        /** @var VenueEntity|null $duplicatedVenueEntity */
        $duplicatedVenueEntity = $this->venueRepository->findByUniqueConstraint(
            $inputDto->getName(), $inputDto->getCountryId(), $inputDto->getCityId()
        );
        if (!is_null($duplicatedVenueEntity) && $duplicatedVenueEntity->getId() != $inputDto->getId()) {
            throw new DuplicateKeyException('Duplicate key (name, country_id, city_id).');
        }

        $country = $this->countryRepository->findById($inputDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException('country_id ' . $inputDto->getCountryId());
        }

        $venueEntityFactory = $this->venueEntityFactory->setEntity($this->inputMapper->map($inputDto))
            ->setCountry($country)
            ->setId($inputDto->getId());

        if (!is_null($inputDto->getCityId())) {
            $city = $this->cityRepository->findById($inputDto->getCityId());
            if (is_null($city)) {
                throw new NoSuchEntityException('city_id ' . $inputDto->getCityId());
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
}