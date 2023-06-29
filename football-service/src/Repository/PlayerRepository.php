<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\PartialPerson;
use Sportal\FootballApi\Model\Person;
use Sportal\FootballApi\Model\Player;
use Sportal\FootballApi\Util\ArrayUtil;
use Sportal\FootballApi\Util\NameUtil;

class PlayerRepository extends PersonRepository
{

    const MAX_RESULTS = 100;

    public function createPartialObject(array $data)
    {
        $player = (new PartialPerson('player'))->setName($data['name'])->setId($data['id']);
        return $player;
    }

    public function clonePartial(Person $player)
    {
        $obj = (new PartialPerson('player'))->setName($player->getName())
            ->setId($player->getId());
        return $obj;
    }

    public function search($name, array $params = null)
    {
        $qb = $this->conn->createQueryBuilder();

        $columns = [
            'p.*'
        ];

        $joins = [
            [
                'object' => 'country',
                'columns' => [
                    'id',
                    'name',
                    'flag'
                ],
                'alias' => 't1'
            ]
        ];

        foreach ($joins as $join) {
            $columns = array_merge($columns, static::formatColumns($join['columns'], $join['alias']));
        }

        $maxResults = isset($params['max_results']) ? min(static::MAX_RESULTS, $params['max_results']) : static::MAX_RESULTS;

        $arrPlayerNames = explode(" ", $name);
        $names = [];
        foreach ($arrPlayerNames as $pName) {
            if (!empty($pName)) {
                $names[] = NameUtil::escapeTsQuery($pName) . ':*';
            }
        }

        $andX = $qb->expr()
            ->andX()
            ->add(
                "to_tsvector('simple', t2.name) @@ to_tsquery('simple', " .
                $qb->createPositionalParameter(implode(' & ', $names)) . ")");


        $qb->select($columns)
            ->from('player', 'p')
            ->innerJoin('p', 'country', 't1', 't1.id = p.country_id')
            ->leftJoin('p', 'ml_content', 't2', 't2.entity=\'player\' AND t2.entity_id=p.id')
            ->where($andX)
            ->setMaxResults($maxResults)
            ->groupBy('p.id,t1.id')
            ->addOrderBy('id', 'asc');

        if (isset($params['team_id'])) {
            $qb->leftJoin('p', 'team_player', 'tp', 'tp.player_id = p.id AND tp.active = true');
            $andX->add('tp.team_id = ' . $qb->createPositionalParameter($params['team_id']));

        }
        $stmt = $qb->execute();
        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            list ($playerAr) = $this->expandRow($row, $joins);
            $player = $this->buildObject($playerAr);
            $data[] = $player;
        }

        return $data;
    }

    public function createObject(array $playerArr)
    {
        /**
         *
         * @var Player $player
         */
        $player = parent::createObject($playerArr);

        if (isset($playerArr['profile'])) {
            $player->setProfile(
                is_array($playerArr['profile']) ? $playerArr['profile'] : json_decode($playerArr['profile'], true));
        }

        if (isset($playerArr['social'])) {
            $player->setSocial(
                is_array($playerArr['social']) ? $playerArr['social'] : json_decode($playerArr['social'], true));
        }

        if (isset($playerArr['position'])) {
            $player->setPosition($playerArr['position']);
        }

        return $player;
    }

    public function getModelClass()
    {
        return Player::class;
    }

    public function getChangedKeys(ModelInterface $existing, ModelInterface $updated)
    {
        $changed = parent::getChangedKeys($existing, $updated);
        $ignored = [];
        if (in_array('social', $changed)) {
            $new = ArrayUtil::getMerged($existing->getSocial(), $updated->getSocial());
            if ($new == $existing->getSocial()) {
                $ignored[] = 'social';
            }
        }
        if (in_array('profile', $changed)) {
            $new = ArrayUtil::getMerged($existing->getProfile(), $updated->getProfile());
            if ($new == $existing->getProfile()) {
                $ignored[] = 'profile';
            }
        }
        if (in_array('first_name', $changed) && $updated->getFirstName() === null) {
            $ignored[] = 'first_name';
        }
        if (in_array('last_name', $changed) && $updated->getLastName() === null) {
            $ignored[] = 'last_name';
        }

        if (in_array('active', $changed) && $updated->getActive() === null) {
            $ignored[] = 'active';
        }

        return array_diff($changed, $ignored);
    }

    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        $existing->setBirthdate($updated->getBirthdate());
        $existing->setCountry($updated->getCountry());
        if ($updated->getFirstName() !== null) {
            $existing->setFirstName($updated->getFirstName());
        }
        if ($updated->getLastName() !== null) {
            $existing->setLastName($updated->getLastName());
        }
        if (!empty($updated->getProfile())) {
            $existing->setProfile(ArrayUtil::getMerged($existing->getProfile(), $updated->getProfile()));
        }
        if (!empty($updated->getSocial())) {
            $existing->setSocial(ArrayUtil::getMerged($existing->getSocial(), $updated->getSocial()));
        }
        if ($updated->getPosition() !== null) {
            $existing->setPosition($updated->getPosition());
        }
        if ($updated->getActive() !== null) {
            $existing->setActive($updated->getActive());
        }
        if ($updated->getGender() !== null) {
            $existing->setGender($updated->getGender());
        }

        return $existing;
    }

    public function getPartialColumns()
    {
        return array_intersect($this->getColumns(),
            [
                'id',
                'name',
                Player::THUMB_KEY,
                Player::IMAGE_KEY
            ]);
    }

    /**
     * @param $id
     * @return Player
     */
    public function findById($id): ?Player
    {
        $tableName = $this->getPersistanceName($this->getModelClass());

        $data = $this->queryTable(
            $tableName,
            ['id' => $id],
            [$this, 'buildObject'],
            $this->getJoin()
        );

        return empty($data) ? null : $data[0];
    }
}