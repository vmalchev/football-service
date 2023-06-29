<?php


namespace Sportal\FootballApi\Application\Referee;


use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Referee\Dto\RefereeDto;
use Sportal\FootballApi\Application\Referee\Dto\RefereeEditDto;
use Sportal\FootballApi\Application\Referee\Dto\RefereePageDto;
use Sportal\FootballApi\Application\Referee\Input\ListAll\Dto as ListAllDto;
use Sportal\FootballApi\Application\Referee\Input\ListAll\Mapper as ListAllMapper;
use Sportal\FootballApi\Application\Referee\Mapper\RefereeEditDtoToEntityMapper;
use Sportal\FootballApi\Application\Referee\Mapper\RefereeEntityToDtoMapper;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Domain\Referee\IRefereeEntityFactory;
use Sportal\FootballApi\Domain\Referee\IRefereeRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;

class RefereeService
{
    private IRefereeRepository $refereeRepository;
    private ICountryRepository $countryRepository;
    private TranslationService $translationService;
    private BlacklistService $blacklistService;
    private RefereeEditDtoToEntityMapper $inputMapper;
    private RefereeEntityToDtoMapper $outputMapper;
    private IRefereeEntityFactory $refereeEntityFactory;
    private ListAllMapper $listAllMapper;

    /**
     * @param IRefereeRepository $refereeRepository
     * @param ICountryRepository $countryRepository
     * @param TranslationService $translationService
     * @param BlacklistService $blacklistService
     * @param RefereeEditDtoToEntityMapper $inputMapper
     * @param RefereeEntityToDtoMapper $outputMapper
     * @param IRefereeEntityFactory $refereeEntityFactory
     */
    public function __construct(IRefereeRepository $refereeRepository,
                                ICountryRepository $countryRepository,
                                TranslationService $translationService,
                                BlacklistService $blacklistService,
                                RefereeEditDtoToEntityMapper $inputMapper,
                                RefereeEntityToDtoMapper $outputMapper,
                                IRefereeEntityFactory $refereeEntityFactory,
                                ListAllMapper $listAllMapper)
    {
        $this->refereeRepository = $refereeRepository;
        $this->countryRepository = $countryRepository;
        $this->translationService = $translationService;
        $this->blacklistService = $blacklistService;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
        $this->refereeEntityFactory = $refereeEntityFactory;
        $this->listAllMapper = $listAllMapper;
    }

    /**
     * @AttachAssets
     * @param ListAllDto $inputDto
     * @return RefereePageDto
     */
    public function findAll(ListAllDto $inputDto): RefereePageDto
    {
        $referees = $this->refereeRepository->findAll($this->listAllMapper->map($inputDto));
        $refereeDtoArray = array_map([$this->outputMapper, 'map'], $referees);
        return new RefereePageDto($refereeDtoArray);
    }

    /**
     * @AttachAssets
     * @param int $id
     * @return RefereeDto
     * @throws NoSuchEntityException
     */
    public function findById(int $id): RefereeDto
    {
        $refereeEntity = $this->refereeRepository->findById($id);
        if ($refereeEntity === null) {
            throw new NoSuchEntityException();
        }
        return $this->outputMapper->map($refereeEntity);
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::REFEREE,operation=EventNotificationOperationType::CREATE)
     * @param RefereeEditDto $refereeDto
     * @return RefereeDto
     * @throws NoSuchEntityException
     */
    public function insert(RefereeEditDto $refereeDto): RefereeDto
    {
        $country = $this->countryRepository->findById($refereeDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException('country_id ' . $refereeDto->getCountryId());
        }

        $refereeEntity = $this->refereeRepository->insert($this->refereeEntityFactory
            ->setEntity($this->inputMapper->map($refereeDto))
            ->setActive(is_null($refereeDto->getActive()) ? true : $refereeDto->getActive())
            ->setCountry($country)
            ->create());

        $translationEntity = new Translation(
            new TranslationKey(TranslationEntity::REFEREE(),
                $refereeEntity->getId(), 'en'),
            new TranslationContent($refereeEntity->getName()),
            new \DateTime()
        );

        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::REFEREE(), $refereeEntity->getId());
        $this->blacklistService->insertNew([$blacklistKey]);

        return $this->outputMapper->map($refereeEntity);
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::REFEREE,operation=EventNotificationOperationType::UPDATE)
     * @param string $id
     * @param RefereeEditDto $refereeDto
     * @return RefereeDto
     * @throws NoSuchEntityException
     */
    public function update(string $id, RefereeEditDto $refereeDto): RefereeDto
    {
        $country = $this->countryRepository->findById($refereeDto->getCountryId());
        if (!$this->refereeRepository->exists($id)) {
            throw new NoSuchEntityException($id);
        }

        if (is_null($country)) {
            throw new NoSuchEntityException($refereeDto->getCountryId());
        }

        $refereeEntity = $this->refereeEntityFactory
            ->setEntity($this->inputMapper->map($refereeDto))
            ->setActive(is_null($refereeDto->getActive()) ? true : $refereeDto->getActive())
            ->setCountry($country)
            ->setId($id)
            ->create();

        $this->refereeRepository->update($refereeEntity);

        $translationEntity = new Translation(
            new TranslationKey(TranslationEntity::REFEREE(),
                $refereeEntity->getId(), 'en'),
            new TranslationContent($refereeEntity->getName()),
            new \DateTime()
        );

        $this->translationService->upsertTranslationEntities([$translationEntity]);

        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::REFEREE(), $id);
        $this->blacklistService->insertNew([$blacklistKey]);

        return $this->outputMapper->map($refereeEntity);
    }
}