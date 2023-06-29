<?php

namespace Sportal\FootballApi\Domain\Group;

interface IGroupDeleteRule
{
    public function validate(IGroupEntity $groupEntity);
}