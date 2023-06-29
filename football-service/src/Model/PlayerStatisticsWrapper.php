<?php
namespace Sportal\FootballApi\Model;

class PlayerStatisticsWrapper implements \JsonSerializable
{

    private $groupBy;

    private $items;

    private $show;

    public function __construct($stats, $groupBy = null, $display = null)
    {
        $this->groupBy = $groupBy;
        if ($this->groupBy !== null) {
            foreach ($stats as $stat) {
                $this->add($stat);
            }
        } else {
            $this->items = $stats;
        }
    }

    public function add(PlayerStatistics $statistics)
    {
        $entity = null;
        if ($this->groupBy == 'team') {
            $entity = $statistics->getTeam();
        } elseif ($this->groupBy == 'tournament' && $statistics->getTournament() instanceof TournamentSeason) {
            $entity = $statistics->getTournament()->getTournament();
        }
        
        if ($entity !== null) {
            $this->items[$entity->getId()][$this->groupBy] = $entity;
            $this->items[$entity->getId()]['items'][] = $statistics;
        }
    }

    public function jsonSerialize()
    {
        if ($this->groupBy !== null) {
            if (!is_null($this->items)) {
                return array_values($this->items);
            } else {
                return [];
            }
        } else {
            return $this->items;
        }
    }
}