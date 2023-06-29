<?php


namespace Sportal\FootballApi\Application\Team\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\Shared\Dto\PageDto;
use Sportal\FootballApi\Application\Shared\Dto\PageMetaDto;
use Sportal\FootballApi\Application\Team;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class TeamPageDto implements JsonSerializable, PageDto, IDto
{
    /**
     * @var Team\Output\Get\Dto[]
     * @SWG\Property()
     */
    private $teams;

    /**
     * @var PageMetaDto
     * @SWG\Property()
     */
    private $page_meta;

    /**
     * TeamCollectionDto constructor.
     * @param Team\Output\Get\Dto[] $teams
     * @param PageMetaDto|null $meta
     */
    public function __construct(array $teams, PageMetaDto $meta = null)
    {
        $this->teams = $teams;
        $this->page_meta = $meta;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return Team\Output\Get\Dto[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return PageMetaDto
     */
    public function getPageMeta(): PageMetaDto
    {
        return $this->page_meta;
    }

    public function setPageMeta(PageMetaDto $pageMetaDto): TeamPageDto
    {
        $this->page_meta = $pageMetaDto;
        return $this;
    }
}