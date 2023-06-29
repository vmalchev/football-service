<?php


namespace Sportal\FootballApi\Application\Team;

use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Team\Input\TeamEditDto;
use Sportal\FootballApi\Application\Team\Input\Mapper;
use Sportal\FootballApi\Application\Team\Dto\TeamPageDto;
use Sportal\FootballApi\Application\Translation\TranslationService;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Coach\ICoachRepository;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Domain\President\IPresidentRepository;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Team\ITeamProfile;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Domain\TeamSquad\TeamActiveCoachModelBuilder;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;


class TeamService
{
    /**
     * @var ITeamRepository
     */
    private ITeamRepository $teamRepository;

    /**
     * @var TranslationService
     */
    private TranslationService $translationService;

    /**
     * @var BlacklistService
     */
    private BlacklistService $blacklistService;

    /**
     * @var ICountryRepository
     */
    private ICountryRepository $countryRepository;

    /**
     * @var IVenueRepository
     */
    private IVenueRepository $venueRepositoty;

    /**
     * @var IPresidentRepository
     */
    private IPresidentRepository $presidentRepository;

    private ICoachRepository $coachRepository;

    private TeamActiveCoachModelBuilder $coachModelBuilder;

    private ITeamProfile $teamProfile;

    private Output\Profile\Mapper $profileMapper;

    private Output\Get\Mapper $getMapper;

    private Mapper $inputMapper;

    public function __construct(
        ITeamRepository $teamRepository,
        TranslationService $translationService,
        BlacklistService $blacklistService,
        ICountryRepository $countryRepository,
        IVenueRepository $venueRepository,
        IPresidentRepository $presidentRepository,
        ICoachRepository $coachRepository,
        TeamActiveCoachModelBuilder $coachModelBuilder,
        ITeamProfile $teamProfile,
        Output\Profile\Mapper $profileMapper,
        Output\Get\Mapper $getMapper,
        Mapper $inputMapper
    )
    {
        $this->teamRepository = $teamRepository;
        $this->translationService = $translationService;
        $this->blacklistService = $blacklistService;
        $this->countryRepository = $countryRepository;
        $this->venueRepositoty = $venueRepository;
        $this->presidentRepository = $presidentRepository;
        $this->coachRepository = $coachRepository;
        $this->coachModelBuilder = $coachModelBuilder;
        $this->teamProfile = $teamProfile;
        $this->profileMapper = $profileMapper;
        $this->getMapper = $getMapper;
        $this->inputMapper = $inputMapper;
    }

    /**
     * @AttachAssets
     * @return TeamPageDto
     */
    public function findAll(): TeamPageDto
    {
        $teams = $this->teamRepository->findAll();
        $teamsDtoArr = array_map([$this->getMapper, 'map'], $teams);
        return new TeamPageDto($teamsDtoArr);
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::TEAM,operation=EventNotificationOperationType::CREATE)
     * @param TeamEditDto $teamEditDto
     * @return Output\Profile\Dto
     * @throws NoSuchEntityException
     */
    public function insert(TeamEditDto $teamEditDto): Output\Profile\Dto
    {
        $country = $this->countryRepository->findById($teamEditDto->getCountryId());
        if (is_null($country)) {
            throw new NoSuchEntityException($teamEditDto->getCountryId() . ' country_id');
        }

        if (!is_null($teamEditDto->getVenueId())) {
            if (is_null($this->venueRepositoty->findById($teamEditDto->getVenueId()))) {
                throw new NoSuchEntityException($teamEditDto->getVenueId() . ' venue_id');
            }
        }

        if (!is_null($teamEditDto->getPresidentId())) {
            if (is_null($this->presidentRepository->findById($teamEditDto->getPresidentId()))) {
                throw new NoSuchEntityException($teamEditDto->getPresidentId() . ' president_id');
            }
        }

        if (!is_null($teamEditDto->getCoachId()) && !$this->coachRepository->exists($teamEditDto->getCoachId())) {
            throw new NoSuchEntityException($teamEditDto->getCoachId() . ' coach_id');
        }

        $teamEntity = $this->teamRepository->insert($this->inputMapper->map($teamEditDto));
        $coach = $this->updateCoach($teamEntity, $teamEditDto->getCoachId());

        $translationEntity = $this->createTranslation($teamEntity);
        $this->translationService->upsertTranslationEntities(array($translationEntity));

        //insert blacklist for the team if not exists
        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::TEAM(), $teamEntity->getId());
        $this->blacklistService->insertNew(array($blacklistKey));

        return $this->profileMapper->map($this->teamProfile->setTeamEntity($teamEntity)->setCurrentCoach($coach));
    }

    private function createTranslation(ITeamEntity $team)
    {
        $translationKey = new TranslationKey(TranslationEntity::TEAM(), $team->getId(), 'en');
        $translationContent = new TranslationContent($team->getName(), $team->getThreeLetterCode(), $team->getShortName());
        $updatedAt = new \DateTime();
        return new Translation($translationKey, $translationContent, $updatedAt);
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::TEAM,operation=EventNotificationOperationType::UPDATE)
     * @param string $id
     * @param TeamEditDto $teamEditDto
     * @return Output\Profile\Dto
     * @throws NoSuchEntityException
     */
    public function update(string $id, TeamEditDto $teamEditDto): Output\Profile\Dto
    {
        $country = $this->countryRepository->findById($teamEditDto->getCountryId());

        if (!$this->teamRepository->exists($id)) {
            throw new NoSuchEntityException($id . ' team');
        }

        if (is_null($country)) {
            throw new NoSuchEntityException($teamEditDto->getCountryId() . ' country_id');
        }

        if (!is_null($teamEditDto->getVenueId())) {
            if (is_null($this->venueRepositoty->findById($teamEditDto->getVenueId()))) {
                throw new NoSuchEntityException($teamEditDto->getVenueId() . ' venue_id');
            }
        }

        if (!is_null($teamEditDto->getPresidentId())) {
            if (is_null($this->presidentRepository->findById($teamEditDto->getPresidentId()))) {
                throw new NoSuchEntityException($teamEditDto->getPresidentId() . ' president_id');
            }
        }

        if (!is_null($teamEditDto->getCoachId()) && !$this->coachRepository->exists($teamEditDto->getCoachId())) {
            throw new NoSuchEntityException($teamEditDto->getCoachId() . ' coach_id');
        }

        $teamEntity = $this->teamRepository->update($this->inputMapper->map($teamEditDto)->withId($id));
        $coach = $this->updateCoach($teamEntity, $teamEditDto->getCoachId());

        $translationEntity = $this->createTranslation($teamEntity);
        $this->translationService->upsertTranslationEntities(array($translationEntity));

        //insert blacklist for the team if not exists
        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::TEAM(), $id);
        $this->blacklistService->insertNew(array($blacklistKey));

        return $this->profileMapper->map($this->teamProfile->setTeamEntity($teamEntity)->setCurrentCoach($coach));
    }

    private function updateCoach(ITeamEntity $team, ?string $coachId): ?ICoachEntity
    {
        $coach = null;
        if ($coachId !== null) {
            $coach = $this->coachRepository->findById($coachId);
        }
        $model = $this->coachModelBuilder->build($team, $coach);
        if ($model !== null) {
            $model->withBlacklist()->upsert();
        }

        return $coach;
    }
}