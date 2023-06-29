<?php

namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Adapter\IEntitySource;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\PartialTeam;
use Sportal\FootballApi\Model\Team;
use Sportal\FootballApi\Util\NameUtil;

class TeamRepository extends Repository implements IEntitySource
{

    const MAX_RESULTS = 100;

    const PARTIAL_COLUMNS = [
        'id',
        'name',
        'three_letter_code',
        'short_name',
        'undecided',
        'national',
        'gender'
    ];

    protected $countryRepository;

    protected $venueRepository;

    public function __construct(
        Connection $conn,
        CacheManager $cacheManager,
        CountryRepository $countryRepository,
        VenueRepository $venueRepository
    ) {
        parent::__construct($conn, $cacheManager);
        $this->countryRepository = $countryRepository;
        $this->venueRepository = $venueRepository;
    }

    /**
     * Clone a team object with only partial information (id, name, logo_filename)
     * @param Team $team
     * @return \Sportal\FootballApi\Model\Team
     */
    public function createPartial(array $teamArr)
    {
        $team = (new PartialTeam())->setName($teamArr['name'])->setId($teamArr['id'])->setThreeLetterCode(
            $teamArr['three_letter_code'] ?? null)
            ->setGender($teamArr['gender'] ?? null);
        if (isset($teamArr['short_name'])) {
            $team->setShortName($teamArr['short_name']);
        }
        if (isset($teamArr['undecided'])) {
            $team->setUndecided($teamArr['undecided']);
        }
        if (isset($teamArr['national'])) {
            $team->setNational($teamArr['national']);
        }

        return $team;
    }

    /**
     * Clone a team object with only partial information (id, name, logo_filename) from a full team object
     * @param Team $team
     * @return \Sportal\FootballApi\Model\Team
     */
    public function clonePartial(Team $team)
    {
        return (new PartialTeam())->setName($team->getName())
            ->setId($team->getId())
            ->setUndecided($team->getUndecided())
            ->setNational($team->getNational())
            ->setThreeLetterCode($team->getThreeLetterCode())
            ->setGender($team->getGender())
            ->setShortName($team->getShortName());
    }

    /**
     *
     * @param array $teamArr
     * @return \Sportal\FootballApi\Model\Team
     */
    public function createObject(array $teamArr)
    {
        $team = (new Team())->setId($teamArr['id'])
            ->setName($teamArr['name'])
            ->setNational($teamArr['national'])
            ->setCountry($teamArr['country'])
            ->setThreeLetterCode($teamArr['three_letter_code'] ?? null)
            ->setGender($teamArr['gender'] ?? null)
            ->setShortName($teamArr['short_name'] ?? null);

        if (isset($teamArr['undecided'])) {
            $team->setUndecided($teamArr['undecided']);
        }
        if (isset($teamArr['venue'])) {
            $team->setVenue($teamArr['venue']);
        }
        if (isset($teamArr['profile'])) {
            $team->setProfile(
                is_array($teamArr['profile']) ? $teamArr['profile'] : json_decode($teamArr['profile'], true)
            );
        }
        if (isset($teamArr['social'])) {
            $team->setSocial(is_array($teamArr['social']) ? $teamArr['social'] : json_decode($teamArr['social'], true));
        }
        return $team;
    }

    public function findByCountry($countryId, $isNationalTeam = null)
    {
        $params = [
            'country_id' => $countryId
        ];

        if ($isNationalTeam !== null) {
            $params['national'] = ($isNationalTeam) ? 'true' : 'false';
        }
        return $this->findAll($params);
    }

    /**
     * @return \Sportal\FootballApi\Model\Team
     */
    public function findById($id)
    {
        $tableName = $this->getPersistanceName($this->getModelClass());

        $data = $this->queryTable(
            $tableName,
            ['id' => $id],
            [$this, 'buildObject'],
            $this->getJoin()
        );

        return $data[0] ?? null;
    }

    /**
     * {@inheritDoc}
     * @return \Sportal\FootballApi\Model\Team
     * @see \Sportal\FootballApi\Repository\Repository::find()
     */
    public function find($id)
    {
        return $this->getByPk(
            $this->getModelClass(),
            ['id' => $id],
            [$this,'buildObject'],
            $this->getJoin()
        );
    }

    public function findAll($filter = [])
    {
        return $this->getAll(
            $this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin()
        );
    }

    public function findExisting(Team $match)
    {
        $teams = $this->queryPersistance(
            [
                'country_id' => $match->getCountry()
                    ->getId(),
                'name' => $match->getName()
            ], [
                $this,
                'buildObject'
            ], $this->getJoin()
        );
        if (count($teams) === 1) {
            return $teams[0];
        }
        return null;
    }

    public function search($name, array $params = null)
    {
        $qb = $this->conn->createQueryBuilder();

        $columns = [
            't.*'
        ];

        $countryColumns = $this->countryRepository->getColumns();
        foreach ($countryColumns as $column) {
            $columns[] = "c.$column as c_$column";
        }

        $maxResults = isset($params['max_results']) ? min(
            static::MAX_RESULTS, $params['max_results']
        ) : static::MAX_RESULTS;
        $names = NameUtil::getTsArr($name);

        $orx = $qb->expr()
            ->orX()
            ->add('t.undecided IS NULL')
            ->add('t.undecided = ' . $qb->createPositionalParameter(false, \PDO::PARAM_BOOL));

        $searchRule =
            "to_tsvector('simple', t2.name) @@ to_tsquery('simple', " .
            $qb->createPositionalParameter(implode(' & ', $names)) . ")";

        $andX = $qb->expr()
            ->andX()
            ->add($orx)
            ->add($searchRule);

        if (isset($params['country_id'])) {
            $andX->add('t.country_id = ' . $qb->createPositionalParameter($params['country_id']));
        }

        $qb->select($columns)
            ->from('team', 't')
            ->innerJoin('t', 'country', 'c', 'c.id = t.country_id')
            ->leftJoin('t', 'ml_content', 't2', 't2.entity=\'team\' AND t2.entity_id=t.id')
            ->where($andX)
            ->setMaxResults($maxResults)
            ->groupBy('t.id,c.id')
            ->addOrderBy('id', 'asc');

        $stmt = $qb->execute();
        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['country'] = [];
            foreach ($countryColumns as $column) {
                $row['country'][$column] = $row["c_$column"];
            }
            $data[] = $this->buildObject($row);
        }

        return $data;
    }

    public function getModelClass()
    {
        return Team::class;
    }

    public function getChangedKeys(ModelInterface $existing, ModelInterface $updated)
    {
        $changed = parent::getChangedKeys($existing, $updated);
        if (($index = array_search('venue_id', $changed)) !== false && $updated->getVenue() === null) {
            unset($changed[$index]);
        }
        return $changed;
    }

    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        $existing->setName($updated->getName());
        $existing->setThreeLetterCode($updated->getThreeLetterCode());
        $existing->setShortName($updated->getShortName());
        $existing->setCountry($updated->getCountry());
        $existing->setNational($updated->getNational());
        $existing->setUndecided($updated->getUndecided());
        $existing->setGender($updated->getGender());
        if ($updated->getVenue() !== null) {
            $existing->setVenue($updated->getVenue());
        }
        $existing->updateJsonColumns($updated);
        return $existing;
    }

    public function getPartialColumns()
    {
        return static::PARTIAL_COLUMNS;
    }

    public function findByIds(array $ids): array
    {
        $filter = [
            [
                'key' => 'id',
                'sign' => 'in',
                'value' => $ids,
            ]
        ];

        $tableName = $this->getPersistanceName($this->getModelClass());

        return $this->queryTable(
            $tableName, $filter, [
                          $this,
                          'createPartial'
                      ]
        );
    }

    protected function buildObject(array $teamArr)
    {
        $teamArr['country'] = $this->countryRepository->createObject($teamArr['country']);
        if (isset($teamArr['venue'])) {
            $teamArr['venue'] = $this->venueRepository->buildObject($teamArr['venue']);
        }
        return $this->createObject($teamArr);
    }

    protected function getJoin()
    {
        static $join = null;
        if ($join === null) {
            $join = [
                [
                    'className' => $this->countryRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->countryRepository->getColumns()
                ],
                [
                    'className' => $this->venueRepository->getModelClass(),
                    'type' => 'left',
                    'columns' => $this->venueRepository->getColumns(),
                    'join' => $this->venueRepository->getJoin()
                ]
            ];
        }
        return $join;
    }
}
