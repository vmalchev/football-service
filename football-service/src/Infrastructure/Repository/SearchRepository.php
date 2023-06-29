<?php


namespace Sportal\FootballApi\Infrastructure\Repository;


use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Search\ISearchRepository;
use Sportal\FootballApi\Domain\Search\SearchQueryBuilder;
use Sportal\FootballApi\Repository\CityRepository;
use Sportal\FootballApi\Repository\CoachRepository;
use Sportal\FootballApi\Repository\CountryRepository;
use Sportal\FootballApi\Repository\PlayerRepository;
use Sportal\FootballApi\Repository\PresidentRepository;
use Sportal\FootballApi\Repository\RefereeRepository;
use Sportal\FootballApi\Repository\TeamRepository;
use Sportal\FootballApi\Repository\TournamentRepository;
use Sportal\FootballApi\Repository\VenueRepository;

class SearchRepository implements ISearchRepository
{
    /**
     * @var SearchQueryBuilder
     */
    private $searchQueryBuilder;

    /**
     * @var PlayerRepository
     */
    private $playerRepository;

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var VenueRepository
     */
    private $venueRepository;

    /**
     * @var CoachRepository
     */
    private $coachRepository;

    /**
     * @var TournamentRepository
     */
    private $tournamentRepository;

    /**
     * @var CountryRepository
     */
    private $countryRepository;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    private RefereeRepository $refereeRepository;

    /**
     * @var PresidentRepository
     */
    private PresidentRepository $presidentRepository;

    /**
     * SearchRepository constructor.
     * @param SearchQueryBuilder $searchQueryBuilder
     * @param PlayerRepository $playerRepository
     * @param TeamRepository $teamRepository
     * @param VenueRepository $venueRepository
     * @param CoachRepository $coachRepository
     * @param TournamentRepository $tournamentRepository
     * @param CountryRepository $countryRepository
     * @param CityRepository $cityRepository
     * @param PresidentRepository $presidentRepository
     * @param RefereeRepository $refereeRepository
     */
    public function __construct(
        SearchQueryBuilder   $searchQueryBuilder,
        PlayerRepository     $playerRepository,
        TeamRepository       $teamRepository,
        VenueRepository      $venueRepository,
        CoachRepository      $coachRepository,
        TournamentRepository $tournamentRepository,
        CountryRepository    $countryRepository,
        CityRepository       $cityRepository,
        PresidentRepository  $presidentRepository,
        RefereeRepository    $refereeRepository
    )
    {
        $this->searchQueryBuilder = $searchQueryBuilder;

        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->venueRepository = $venueRepository;
        $this->coachRepository = $coachRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->refereeRepository = $refereeRepository;
        $this->presidentRepository = $presidentRepository;
        $this->refereeRepository = $refereeRepository;
        $this->presidentRepository = $presidentRepository;
        $this->refereeRepository = $refereeRepository;
    }


    public function searchBy(IDto $queryDto)
    {
        $stmt = $this->searchQueryBuilder->build($queryDto)->execute();

        $ENTITY_REPOSITORY_MAP = [
            EntityType::PLAYER()->getValue() => $this->playerRepository,
            EntityType::TEAM()->getValue() => $this->teamRepository,
            EntityType::COACH()->getValue() => $this->coachRepository,
            EntityType::VENUE()->getValue() => $this->venueRepository,
            EntityType::REFEREE()->getValue() => $this->refereeRepository,
            EntityType::TOURNAMENT()->getValue() => $this->tournamentRepository,
            EntityType::CITY()->getValue() => $this->cityRepository,
            EntityType::PRESIDENT()->getValue() => $this->presidentRepository,
        ];

        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (isset($ENTITY_REPOSITORY_MAP[$row['entity_type']])) {
                $repository = $ENTITY_REPOSITORY_MAP[$row['entity_type']];

                $columns = json_decode($row[$row['entity_type']], true);

                if (isset($row['country'])) {
                    $columns['country'] = $this->countryRepository->createObject(json_decode($row['country'], true));
                }

                if (isset($row['venue'])) {
                    $columns['venue'] = $this->venueRepository->createObject(json_decode($row['venue'], true));
                } else if (isset($row['team_venue'])) {
                    $columns['venue'] = $this->venueRepository->createObject(json_decode($row['team_venue'], true));
                }

                if (isset($row['coach'])) {
                    $columns['coach'] = $this->coachRepository->createPartialObject(json_decode($row['coach'], true));
                }

                if (isset($row['president'])) {
                    $columns['president'] = $this->presidentRepository->createObject(json_decode($row['president'], true));
                }

                if (isset($row['referee'])) {
                    $columns['referee'] = $this->refereeRepository->createPartialObject(json_decode($row['referee'], true));
                }

                $origins = explode(',', $row['origin']);
                $originDetail = [];
                foreach ($origins as $origin) {
                    $originDetail[]['value'] = $origin;
                }

                $data[] = [
                    'entity' => $repository->createObject($columns),
                    'origin' => $origins[0],
                    'origin_detail' => $originDetail,
                    'entity_type' => $row['entity_type'],
                ];
            }
        }

        return $data;
    }
}

