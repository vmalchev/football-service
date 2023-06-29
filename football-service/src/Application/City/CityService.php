<?php


namespace Sportal\FootballApi\Application\City;


use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Application\City\Dto\CityDto;
use Sportal\FootballApi\Application\City\Dto\CityEditDto;
use Sportal\FootballApi\Application\City\Mapper\CityEditDtoToEntityMapper;
use Sportal\FootballApi\Application\City\Mapper\CityEntityToDtoMapper;
use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\City\ICityEntityFactory;
use Sportal\FootballApi\Domain\City\ICityRepository;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\City\CityEntity;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;


class CityService
{
    private ICityRepository $cityRepository;
    private ICountryRepository $countryRepository;
    private TranslationService $translationService;
    private BlacklistService $blacklistService;
    private CityEditDtoToEntityMapper $inputMapper;
    private CityEntityToDtoMapper $outputMapper;
    private ICityEntityFactory $cityEntityFactory;

    /**
     * CityService constructor.
     * @param ICityRepository $cityRepository
     * @param ICountryRepository $countryRepository
     * @param TranslationService $translationService
     * @param BlacklistService $blacklistService
     * @param CityEditDtoToEntityMapper $inputMapper
     * @param CityEntityToDtoMapper $outputMapper
     * @param ICityEntityFactory $cityEntityFactory
     */
    public function __construct(ICityRepository $cityRepository, ICountryRepository $countryRepository, TranslationService $translationService, BlacklistService $blacklistService, CityEditDtoToEntityMapper $inputMapper, CityEntityToDtoMapper $outputMapper, ICityEntityFactory $cityEntityFactory)
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->translationService = $translationService;
        $this->blacklistService = $blacklistService;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
        $this->cityEntityFactory = $cityEntityFactory;
    }

    /**
     * @AttachEventNotification(object=EventNotificationEntityType::CITY,operation=EventNotificationOperationType::UPDATE)
     * @param string $id
     * @param CityEditDto $cityEditDto
     * @return CityDto
     * @throws DuplicateKeyException
     * @throws NoSuchEntityException
     */
    public function update(string $id, CityEditDto $cityEditDto): CityDto
    {
        if (!$this->cityRepository->exists($id)) {
            throw new NoSuchEntityException('city_id ' . $id);
        }

        /** @var CityEntity|null $duplicateCityEntity */
        $duplicateCityEntity = $this->cityRepository->findByUniqueConstraint($cityEditDto->getName(), $cityEditDto->getCountryId());
        if (!is_null($duplicateCityEntity) && $duplicateCityEntity->getId() != $id) {
            throw new DuplicateKeyException('Duplicate key (name, country_id).');
        }

        $country = $this->countryRepository->findById($cityEditDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException('country_id' . $cityEditDto->getCountryId());
        }

        $cityEntity = $this->cityEntityFactory->setEntity($this->inputMapper->map($cityEditDto))
            ->setCountry($country)
            ->setId($id)
            ->create();

        $updatedCityEntity = $this->cityRepository->update($cityEntity);

        $translationKey = new TranslationKey(TranslationEntity::CITY(), $updatedCityEntity->getId(), 'en');
        $translationContent = new TranslationContent($updatedCityEntity->getName());
        $updatedAt = new \DateTime();
        $translationEntity = new Translation($translationKey, $translationContent, $updatedAt);
        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::CITY(), $updatedCityEntity->getId());
        $this->blacklistService->insertNew([$blacklistKey]);

        return $this->outputMapper->map($cityEntity);
    }

    /**
     * @AttachEventNotification(object=EventNotificationEntityType::CITY,operation=EventNotificationOperationType::CREATE)
     * @param CityEditDto $cityEditDto
     * @return CityDto
     * @throws DuplicateKeyException
     * @throws NoSuchEntityException
     */
    public function insert(CityEditDto $cityEditDto): CityDto
    {
        $country = $this->countryRepository->findById($cityEditDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException('country_id ' . $cityEditDto->getCountryId());
        }

        /** @var CityEntity|null $duplicateCityEntity */
        $duplicateCityEntity = $this->cityRepository->findByUniqueConstraint($cityEditDto->getName(), $cityEditDto->getCountryId());
        if (!is_null($duplicateCityEntity)) {
            throw new DuplicateKeyException('Duplicate key (name, country_id).');
        }

        $cityEntity = $this->cityRepository->insert($this->cityEntityFactory
            ->setEntity($this->inputMapper->map($cityEditDto))
            ->setCountry($country)
            ->create());

        $translationKey = new TranslationKey(TranslationEntity::CITY(), $cityEntity->getId(), 'en');
        $translationContent = new TranslationContent($cityEntity->getName());
        $updatedAt = new \DateTime();
        $translationEntity = new Translation($translationKey, $translationContent, $updatedAt);

        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::CITY(), $cityEntity->getId());
        $this->blacklistService->insertNew([$blacklistKey]);

        return $this->outputMapper->map($cityEntity);
    }
}