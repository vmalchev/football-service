<?php


namespace Sportal\FootballApi\Application\Venue\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\Shared\Dto\PageDto;
use Sportal\FootballApi\Application\Shared\Dto\PageMetaDto;

/**
 * @SWG\Definition()
 */
class VenuePageDto implements JsonSerializable, PageDto
{
    /**
     * @var VenueDto[]
     * @SWG\Property()
     */
    private $venues;

    /**
     * @var PageMetaDto
     * @SWG\Property()
     */
    private $page_meta;

    /**
     * VenueCollectionDto constructor.
     * @param VenueDto[] $venues
     * @param PageMetaDto $meta
     */
    public function __construct(array $venues, PageMetaDto $meta = null)
    {
        $this->venues = $venues;
        $this->page_meta = $meta;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function setPageMeta(PageMetaDto $pageMetaDto): VenuePageDto
    {
        $this->page_meta = $pageMetaDto;
        return $this;
    }
}