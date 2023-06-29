<?php
namespace Sportal\FootballApi\Model;

interface ModelInterface
{

    /**
     * Returns a map of the model as it should be stored in persistance.
     * @return array key => value pairs
     */
    public function getPersistanceMap();

    /**
     * Returns a map of the models primary keys.
     * @return array key => value pairs
     */
    public function getPrimaryKeyMap();
}