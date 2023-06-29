<?php


namespace Sportal\FootballApi\Application\Venue\Output\ListAll;

use JsonSerializable;
use Sportal\FootballApi\Application\Shared\Dto\PageDto;
use Sportal\FootballApi\Application\Shared\Dto\PageMetaDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_VenuesList")
 */
class Dto implements JsonSerializable, PageDto
{
    /**
     * @var ListVenueDto[]
     * @SWG\Property(property="venues")
     */
    private $venues;

    /**
     * @var PageMetaDto
     * @SWG\Property(property="page_meta")
     */
    private $page_meta;

    /**
     * VenueCollectionDto constructor.
     * @param ListVenueDto[] $venues
     * @param PageMetaDto|null $meta
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

    public function setPageMeta(PageMetaDto $pageMetaDto): Dto
    {
        $this->page_meta = $pageMetaDto;
        return $this;
    }
}