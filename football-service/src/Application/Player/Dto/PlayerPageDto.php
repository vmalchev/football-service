<?php


namespace Sportal\FootballApi\Application\Player\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\Shared\Dto\PageDto;
use Sportal\FootballApi\Application\Shared\Dto\PageMetaDto;

/**
 * @SWG\Definition()
 */
class PlayerPageDto implements JsonSerializable, PageDto
{
    /**
     * @var PlayerDto[]
     * @SWG\Property()
     */
    private $players;

    /**
     * @var PageMetaDto
     * @SWG\Property()
     */
    private $page_meta;

    /**
     * PlayerCollectionDto constructor.
     * @param array|PlayerDto[] $players
     * @param PageMetaDto $page_meta
     */
    public function __construct($players, PageMetaDto $page_meta = null)
    {
        $this->players = $players;
        $this->page_meta = $page_meta;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function setPageMeta(PageMetaDto $pageMetaDto): PageDto
    {
        $this->page_meta = $pageMetaDto;
        return $this;
    }
}