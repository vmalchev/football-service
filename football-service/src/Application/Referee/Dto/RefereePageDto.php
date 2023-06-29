<?php


namespace Sportal\FootballApi\Application\Referee\Dto;


use JsonSerializable;
use Sportal\FootballApi\Application\Shared\Dto\PageDto;
use Sportal\FootballApi\Application\Shared\Dto\PageMetaDto;

/**
 * @SWG\Definition()
 */
class RefereePageDto implements JsonSerializable, PageDto
{
    /**
     * @var RefereeDto[]
     * @SWG\Property()
     */
    private $referees;

    /**
     * @var PageMetaDto
     * @SWG\Property()
     */
    private $page_meta;

    /**
     * RefereePageDto constructor.
     * @param RefereeDto[] $referees
     * @param PageMetaDto $page_meta
     */
    public function __construct(array $referees, PageMetaDto $page_meta = null)
    {
        $this->referees = $referees;
        $this->page_meta = $page_meta;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function setPageMeta(PageMetaDto $pageMetaDto): RefereePageDto
    {
        $this->page_meta = $pageMetaDto;
        return $this;
    }
}