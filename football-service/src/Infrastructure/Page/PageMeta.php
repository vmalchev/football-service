<?php

namespace Sportal\FootballApi\Infrastructure\Page;

class PageMeta
{
    private int $total;

    private ?int $offset;

    private ?int $limit;

    /**
     * PageMeta constructor.
     * @param int $total
     * @param int|null $offset
     * @param int|null $limit
     */
    public function __construct(int $total, ?int $offset, ?int $limit)
    {
        $this->total = $total;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }



}