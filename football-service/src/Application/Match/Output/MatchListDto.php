<?php

namespace Sportal\FootballApi\Application\Match\Output;

interface MatchListDto
{

    /**
     * @return array
     */
    public function getMatches(): array;
}