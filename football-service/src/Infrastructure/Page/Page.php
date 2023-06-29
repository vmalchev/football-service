<?php


namespace Sportal\FootballApi\Infrastructure\Page;


class Page
{
    private array $data;

    private PageMeta $pageMeta;

    /**
     * Page constructor.
     * @param array $data
     * @param PageMeta $pageMeta
     */
    public function __construct(array $data, PageMeta $pageMeta)
    {
        $this->data = $data;
        $this->pageMeta = $pageMeta;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return PageMeta
     */
    public function getPageMeta(): PageMeta
    {
        return $this->pageMeta;
    }
}