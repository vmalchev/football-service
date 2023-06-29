<?php


namespace Sportal\FootballApi\Application\Coach;


use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Application\Coach\Dto\CoachDto;
use Sportal\FootballApi\Application\Coach\Dto\CoachEditDto;
use Sportal\FootballApi\Application\Coach\Dto\CoachPageDto;
use Sportal\FootballApi\Application\Coach\Mapper\CoachEditDtoToEntityMapper;
use Sportal\FootballApi\Application\Coach\Mapper\CoachEntityToDtoMapper;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Coach\ICoachEntityFactory;
use Sportal\FootballApi\Domain\Coach\ICoachRepository;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;

class CoachService
{
    private ICoachRepository $coachRepository;
    private ICountryRepository $countryRepository;
    private TranslationService $translationService;
    private BlacklistService $blacklistService;
    private CoachEntityToDtoMapper $outputMapper;
    private CoachEditDtoToEntityMapper $inputMapper;
    private ICoachEntityFactory $coachEntityFactory;

    public function __construct(
        ICoachRepository $coachRepository,
        ICountryRepository $countryRepository,
        TranslationService $translationService,
        BlacklistService $blacklistService,
        CoachEntityToDtoMapper $outputMapper,
        CoachEditDtoToEntityMapper $inputMapper,
        ICoachEntityFactory $coachEntityFactory
    ) {
        $this->coachRepository = $coachRepository;
        $this->countryRepository = $countryRepository;
        $this->translationService = $translationService;
        $this->blacklistService = $blacklistService;
        $this->outputMapper = $outputMapper;
        $this->inputMapper = $inputMapper;
        $this->coachEntityFactory = $coachEntityFactory;
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::COACH,operation=EventNotificationOperationType::UPDATE)
     * @param string $id
     * @param CoachEditDto $coachDto
     * @return CoachDto
     * @throws NoSuchEntityException
     */
    public function update(string $id, CoachEditDto $coachDto): CoachDto
    {
        if (!$this->coachRepository->exists($id)) {
            throw new NoSuchEntityException($id);
        }

        $country = $this->countryRepository->findById($coachDto->getCountryId());

        if (is_null($country)) {
            throw new NoSuchEntityException('country_id ' . $coachDto->getCountryId());
        }

        $coachEntity = $this->coachEntityFactory
            ->setEntity($this->inputMapper->map($coachDto))
            ->setCountry($country)
            ->setId($id)
            ->create();

        $this->coachRepository->update($coachEntity);

        $translationEntity = $this->createTranslation($id, $coachEntity->getName());
        $this->translationService->upsertTranslationEntities(array($translationEntity));

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::COACH(), $id);
        $this->blacklistService->insertNew(array($blacklistKey));

        return $this->findById($id);
    }

    private function createTranslation(string $id, string $name)
    {
        $translationKey = new TranslationKey(TranslationEntity::COACH(), $id, 'en');
        $translationContent = new TranslationContent($name);
        $updatedAt = new \DateTime();
        return new Translation($translationKey, $translationContent, $updatedAt);
    }

    /**
     * @AttachAssets
     * @param int $id
     * @return CoachDto
     * @throws NoSuchEntityException
     */
    public function findById(int $id): CoachDto
    {
        $coachEntity = $this->coachRepository->findById($id);
        if (is_null($coachEntity)) {
            throw new NoSuchEntityException();
        }
        return $this->outputMapper->map($coachEntity);
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::COACH,operation=EventNotificationOperationType::CREATE)
     * @param CoachEditDto $coachDto
     * @return CoachDto
     * @throws NoSuchEntityException
     */
    public function insert(CoachEditDto $coachDto): CoachDto
    {
        $country = $this->countryRepository->findById($coachDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException($coachDto->getCountryId() . ' country_id');
        }

        $coachEntity = $this->coachRepository->insert(
            $this->coachEntityFactory
                ->setEntity($this->inputMapper->map($coachDto))
                ->setCountry($country)
                ->create()
        );

        $translationEntity = $this->createTranslation($coachEntity->getId(), $coachEntity->getName());
        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::COACH(), $coachEntity->getId());
        $this->blacklistService->insertNew([$blacklistKey]);

        return $this->outputMapper->map($coachEntity);
    }

    /**
     * @AttachAssets
     * @return CoachPageDto
     */
    public function findAll(): CoachPageDto
    {
        $coaches = $this->coachRepository->findAll();
        $venueDtoArray = array_map([$this->outputMapper, 'map'], $coaches);
        return new CoachPageDto($venueDtoArray);
    }
}