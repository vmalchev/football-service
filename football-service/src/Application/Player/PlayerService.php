<?php


namespace Sportal\FootballApi\Application\Player;


use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Application\City\Mapper\CityEntityToDtoMapper;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Player\Dto\PlayerDto;
use Sportal\FootballApi\Application\Player\Dto\PlayerEditDto;
use Sportal\FootballApi\Application\Player\Dto\PlayerPageDto;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\City\ICityRepository;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\Player\PlayerEditEntity;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;


class PlayerService
{
    private IPlayerRepository $playerRepository;
    private TranslationService $translationService;
    private BlacklistService $blacklistService;
    private ICountryRepository $countryRepository;
    private ICityRepository $cityRepository;
    private CityEntityToDtoMapper $cityOutputMapper;

    public function __construct(
        IPlayerRepository $playerRepository,
        TranslationService $translationService,
        BlacklistService $blacklistService,
        ICountryRepository $countryRepository,
        ICityRepository $cityRepository,
        CityEntityToDtoMapper $cityOutputMapper
    ) {
        $this->playerRepository = $playerRepository;
        $this->translationService = $translationService;
        $this->blacklistService = $blacklistService;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->cityOutputMapper = $cityOutputMapper;
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::PLAYER,operation=EventNotificationOperationType::UPDATE)
     * @param string $id
     * @param PlayerEditDto $playerDto
     * @return PlayerDto
     * @throws NoSuchEntityException
     */
    public function update(string $id, PlayerEditDto $playerDto)
    {
        if (!$this->playerRepository->exists($id)) {
            throw new NoSuchEntityException($id);
        }

        if (is_null($this->countryRepository->findById($playerDto->getCountryId()))) {
            throw new NoSuchEntityException('country_id ' . $playerDto->getCountryId());
        }

        $playerCity = null;
        if (!is_null($playerDto->getBirthCityId())) {
            $playerCity = $this->cityRepository->findById($playerDto->getBirthCityId());
            if (is_null($playerCity)) {
                throw new NoSuchEntityException('city_id ' . $playerDto->getBirthCityId());
            }
        }

        $playerEditEntity = PlayerEditEntity::fromPlayerEditDto($playerDto)->withId($id);
        /** @var IPlayerEntity $playerEntity */
        $playerEntity = $this->playerRepository->update($playerEditEntity);

        if (is_null($playerEntity)) {
            throw new \Exception();
        }

        $translationEntity = $this->createTranslation($id, $playerEntity->getName());
        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::PLAYER(), $id);
        $this->blacklistService->insertNew([$blacklistKey]);

        return PlayerDto::fromPlayerEntity(
            $playerEntity,
            CountryDto::fromCountryEntity($playerEntity->getCountry()),
            !is_null($playerCity) ? $this->cityOutputMapper->map($playerCity) : null
        );
    }

    private function createTranslation(string $id, string $name)
    {
        $translationKey = new TranslationKey(TranslationEntity::PLAYER(), $id, 'en');
        $translationContent = new TranslationContent($name);
        $updatedAt = new \DateTime();
        return new Translation($translationKey, $translationContent, $updatedAt);
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::PLAYER,operation=EventNotificationOperationType::CREATE)
     * @param PlayerEditDto $playerDto
     * @return PlayerDto
     * @throws NoSuchEntityException
     */
    public function create(PlayerEditDto $playerDto)
    {
        $playerCountry = $this->countryRepository->findById($playerDto->getCountryId());
        if (is_null($playerCountry)) {
            throw new NoSuchEntityException('country_id ' . $playerDto->getCountryId());
        }

        $playerCity = null;
        if (!is_null($playerDto->getBirthCityId())) {
            $playerCity = $this->cityRepository->findById($playerDto->getBirthCityId());
            if (is_null($playerCity)) {
                throw new NoSuchEntityException('city_id ' . $playerDto->getBirthCityId());
            }
        }

        $playerEntity = $this->playerRepository->insert(PlayerEditEntity::fromPlayerEditDto($playerDto));

        if (!empty($playerEntity)) {
            $translationEntity = $this->createTranslation($playerEntity->getId(), $playerEntity->getName());
            $this->translationService->upsertTranslationEntities([$translationEntity]);

            $blacklistKey = new BlacklistKey(
                BlacklistType::ENTITY(), BlacklistEntityName::PLAYER(), $playerEntity->getId()
            );
            $this->blacklistService->insertNew([$blacklistKey]);
        }

        return PlayerDto::fromPlayerEntity(
            $playerEntity, CountryDto::fromCountryEntity($playerCountry),
            !is_null($playerCity) ? $this->cityOutputMapper->map($playerCity) : null
        );
    }

    /**
     * @AttachAssets
     * @return PlayerPageDto
     */
    public function findAll()
    {
        $players = $this->playerRepository->findAll();
        $playerDtoArr = array_map(
            function (IPlayerEntity $playerEntity) {
                return PlayerDto::fromPlayerEntity(
                    $playerEntity,
                    CountryDto::fromCountryEntity($playerEntity->getCountry()),
                    !is_null($playerEntity->getBirthCity()) ? $this->cityOutputMapper->map(
                        $playerEntity->getBirthCity()
                    ) : null
                );
            }, $players
        );
        return new PlayerPageDto($playerDtoArr);
    }

    /**
     * @AttachAssets
     * @param int $id
     * @return PlayerDto
     * @throws NoSuchEntityException
     * @deprecated
     */
    public function findById(int $id): PlayerDto
    {
        $playerEntity = $this->playerRepository->findById($id);

        if (is_null($playerEntity)) {
            throw new NoSuchEntityException();
        }

        return PlayerDto::fromPlayerEntity(
            $playerEntity,
            CountryDto::fromCountryEntity($playerEntity->getCountry()),
            !is_null($playerEntity->getBirthCity()) ? $this->cityOutputMapper->map($playerEntity->getBirthCity()) : null
        );
    }
}
