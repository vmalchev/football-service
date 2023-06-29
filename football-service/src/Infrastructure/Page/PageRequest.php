<?php


namespace Sportal\FootballApi\Infrastructure\Page;


class PageRequest
{
    const MAX_LIMIT = 1000;

    private int $offset;

    private int $limit;

    /**
     * PageRequest constructor.
     * @param int $offset
     * @param int $limit
     */
    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

}