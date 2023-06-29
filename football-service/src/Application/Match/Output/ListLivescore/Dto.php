<?php


namespace Sportal\FootballApi\Application\Match\Output\ListLivescore;

use JsonSerializable;
use Sportal\FootballApi\Application\Match;
use Swagger\Annotations as SWG;


/**
 * @SWG\Definition(definition="v2_MatchesLivescore")
 */
class Dto implements JsonSerializable, Match\Output\MatchListDto
{
    /**
     * @SWG\Property(property="matches")
     * @var Match\Output\Get\Dto[]
     */
    private array $matches;

    /**
     * Dto constructor.
     * @param Match\Output\Get\Dto[] $matches
     */
    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    /**
     * @inheritDoc
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}