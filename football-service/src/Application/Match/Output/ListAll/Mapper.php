<?php


namespace Sportal\FootballApi\Application\Match\Output\ListAll;

use Sportal\FootballApi\Application\Match;
use Sportal\FootballApi\Domain\Match\IMatchProfile;

class Mapper
{
    private Match\Output\Get\Mapper $matchMapper;

    /**
     * Mapper constructor.
     * @param Match\Output\Get\Mapper $matchMapper
     */
    public function __construct(Match\Output\Get\Mapper $matchMapper)
    {
        $this->matchMapper = $matchMapper;
    }

    /**
     * @param IMatchProfile[] $matchProfiles
     * @return Dto
     */
    public function map(array $matchProfiles): Dto
    {
        return new Dto(array_map([$this->matchMapper, 'map'], $matchProfiles));
    }

}