<?php
namespace Sportal\FootballApi\Model;

interface Comparable
{

    /**
     * Check if a model is equal to another model of the same type
     * @return boolean
     */
    public function equals($other);
}