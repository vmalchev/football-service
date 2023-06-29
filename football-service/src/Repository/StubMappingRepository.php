<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\TournamentSeasonStage;

class StubMappingRepository implements MappingRepositoryInterface
{

    private $map;

    public function __construct()
    {
        $this->map = [];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\MappingRepositoryInterface::setMapping()
     */
    public function setMapping($object, $feedId, $ownId)
    {
        if (! isset($this->map[$object])) {
            $this->map[$object] = array();
        }
        
        $this->map[$object][$feedId] = $ownId;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\MappingRepositoryInterface::getMappingId()
     */
    public function getOwnId($object, $id)
    {
        if (isset($this->map[$object][$id])) {
            return $this->map[$object][$id];
        }
        
        return null;
    }
}