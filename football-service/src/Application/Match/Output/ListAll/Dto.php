<?php


namespace Sportal\FootballApi\Application\Match\Output\ListAll;

use JsonSerializable;
use Sportal\FootballApi\Application\Match;
use Sportal\FootballApi\Application\Shared\Dto\PageDto;
use Sportal\FootballApi\Application\Shared\Dto\PageMetaDto;
use Swagger\Annotations as SWG;


/**
 * @SWG\Definition(definition="v2_Matches")
 */
class Dto implements JsonSerializable, PageDto, Match\Output\MatchListDto
{
    /**
     * @SWG\Property(property="matches")
     * @var Match\Output\Get\Dto[]
     */
    private array $matches;

    /**
     * @var PageMetaDto
     * @SWG\Property()
     */
    private $page_meta;

    /**
     * Dto constructor.
     * @param Match\Output\Get\Dto[] $matches
     */
    public function __construct(array $matches, PageMetaDto $page_meta = null)
    {
        $this->matches = $matches;
        $this->page_meta = $page_meta;
    }

    public function setPageMeta(PageMetaDto $pageMetaDto): Dto
    {
        $this->page_meta = $pageMetaDto;
        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getMatches(): array
    {
        return $this->matches;
    }
}