<?php


namespace Sportal\FootballApi\Application\Venue\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Venue\Input;
use Sportal\FootballApi\Application\Venue\Output;
use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Domain\City\ICityRepository;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


final class Create implements IService
{
    private IVenueRepository $venueRepository;

    private Output\Create\Mapper $outputMapper;

    private IVenueEntityFactory $venueEntityFactory;

    private ICityRepository $cityRepository;

    private ICountryRepository $countryRepository;

    private Input\Create\Mapper $inputMapper;

    private TranslationService $translationService;

    private BlacklistService $blacklistService;


    /**
     * VenueService constructor.
     * @param IVenueRepository $venueRepository
     * @param Input\Create\Mapper $inputMapper
     * @param Output\Create\Mapper $outputMapper
     * @param IVenueEntityFactory $venueEntityFactory
     * @param ICityRepository $cityRepository ;
     * @param BlacklistService $blacklistService
     * @param TranslationService $translationService
     * @param ICountryRepository $countryRepository ;
     */
    public function __construct(
        IVenueRepository $venueRepository,
        Output\Create\Mapper $outputMapper,
        TranslationService $translationService,
        ICityRepository $cityRepository,
        BlacklistService $blacklistService,
        ICountryRepository $countryRepository,
        Input\Create\Mapper $inputMapper,
        IVenueEntityFactory $venueEntityFactory
    ) {
        $this->venueRepository = $venueRepository;
        $this->outputMapper = $outputMapper;
        $this->blacklistService = $blacklistService;
        $this->venueEntityFactory = $venueEntityFactory;
        $this->translationService = $translationService;
        $this->cityRepository = $cityRepository;
        $this->inputMapper = $inputMapper;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @AttachAssets
     * @param Input\Create\Dto $inputDto
     * @return Output\Create\Dto
     * @throws NoSuchEntityException
     * @throws DuplicateKeyException
     */
    public function process(IDto $inputDto): Output\Create\Dto
    {
        $country = $this->countryRepository->findById($inputDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException('country_id ' . $inputDto->getCountryId());
        }

        $city = null;
        if (!is_null($inputDto->getCityId())) {
            $city = $this->cityRepository->findById($inputDto->getCityId());
            if (is_null($city)) {
                throw new NoSuchEntityException('city_id ' . $inputDto->getCityId());
            }
        }


        $duplicatedVenueEntity = $this->venueRepository->findByUniqueConstraint(
            $inputDto->getName(), $inputDto->getCountryId(), $inputDto->getCityId()
        );
        if (!is_null($duplicatedVenueEntity)) {
            throw new DuplicateKeyException('Duplicate key (name, country_id, city_id).');
        }

        $venueEntity = $this->venueRepository->insert(
            $this->venueEntityFactory
                ->setEntity($this->inputMapper->map($inputDto))
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

    private function createTranslation(string $id, string $name)
    {
        $translationKey = new TranslationKey(TranslationEntity::VENUE(), $id, 'en');
        $translationContent = new TranslationContent($name);
        $updatedAt = new \DateTime();
        return new Translation($translationKey, $translationContent, $updatedAt);
    }
}