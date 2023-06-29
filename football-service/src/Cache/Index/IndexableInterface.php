<?php
namespace Sportal\FootballApi\Cache\Index;

use Sportal\FootballApi\Cache\Entity\CacheEntityInterface;

interface IndexableInterface extends CacheEntityInterface
{

    /**
     * @return array
     */
    public function getSortedIndecies();
}