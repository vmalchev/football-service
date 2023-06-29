<?php
namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Cache\Map\MappableInterface;
use Sportal\FootballApi\Database\ModelInterface as DbModel;

class TournamentOrder implements DbModel, MappableInterface
{

    const CLIENT_INDEX = 'client_code';

    const TOURNAMENT_INDEX = 'tournament_id';

    private $clientCode;

    private $tournamentId;

    private $sortorder;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Database\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'client_code' => $this->clientCode,
            'tournament_id' => $this->tournamentId,
            'sortorder' => $this->sortorder
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Database\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'client_code' => $this->clientCode,
            'tournament_id' => $this->tournamentId
        ];
    }

    public function getClientCode()
    {
        return $this->clientCode;
    }

    public function setClientCode($clientCode)
    {
        $this->clientCode = $clientCode;
        return $this;
    }

    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    public function setTournamentId($tournamentId)
    {
        $this->tournamentId = (int) $tournamentId;
        return $this;
    }

    public function getSortorder()
    {
        return $this->sortorder;
    }

    public function setSortorder($sortorder)
    {
        $this->sortorder = (int) $sortorder;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\Map\MappableInterface::getMapEntries()
     */
    public function getMapEntries()
    {
        return [
            $this->clientCode => [
                $this->tournamentId => $this->sortorder
            ]
        ];
    }
}