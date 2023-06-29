<?php


namespace Sportal\FootballApi\Application\Coach\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\Shared\Dto\PageDto;
use Sportal\FootballApi\Application\Shared\Dto\PageMetaDto;

/**
 * @SWG\Definition()
 */
class CoachPageDto implements JsonSerializable, PageDto
{
    /**
     * @var CoachDto[]
     * @SWG\Property()
     */
    private $coaches;

    /**
     * @var PageMetaDto
     * @SWG\Property()
     */
    private $page_meta;

    /**
     * CoachPageDto constructor.
     * @param array $coaches
     * @param PageMetaDto|null $meta
     */
    public function __construct(array $coaches, PageMetaDto $meta = null)
    {
        $this->coaches = $coaches;
        $this->page_meta = $meta;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function setPageMeta(PageMetaDto $pageMetaDto): CoachPageDto
    {
        $this->page_meta = $pageMetaDto;
        return $this;
    }
}