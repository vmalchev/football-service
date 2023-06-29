<?php
namespace Sportal\FootballApi\Model;

interface Translateable
{

    /**
     * Return list of Models contained in the object which can be translated
     * @return MlContainerInterface[]
     */
    public function getMlContentModels();
}