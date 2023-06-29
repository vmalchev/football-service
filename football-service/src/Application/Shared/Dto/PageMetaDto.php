<?php


namespace Sportal\FootballApi\Application\Shared\Dto;

use JsonSerializable;
use Sportal\FootballApi\Infrastructure\Page\PageMeta;

/**
 * @SWG\Definition()
 */
class PageMetaDto implements JsonSerializable
{
    /**
     * @var int
     * @SWG\Property()
     */
    private $total;

    /**
     * @var int
     * @SWG\Property()
     */
    private $offset;

    /**
     * @var int
     * @SWG\Property()
     */
    private $limit;

    /**
     * CollectionMeta constructor.
     * @param int $total
     * @param int $offset
     * @param int $limit
     */
    public function __construct(int $total, int $offset, int $limit)
    {
        $this->total = $total;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function create(PageMeta $meta)
    {
        return new PageMetaDto($meta->getTotal(), $meta->getOffset(), $meta->getLimit());
    }
}