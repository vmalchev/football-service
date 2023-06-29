<?php


namespace Sportal\FootballApi\Infrastructure\Page;


class PageDataProvider
{
    private ?PageRequest $request = null;

    private PageMeta $meta;

    /**
     * @return PageRequest|null
     */
    public function getRequest(): ?PageRequest
    {
        return $this->request;
    }

    /**
     * @param PageRequest|null $request
     * @return void
     */
    public function setRequest(?PageRequest $request): void
    {
        $this->request = $request;
    }

    /**
     * @return PageMeta
     */
    public function getMeta(): PageMeta
    {
        return $this->meta;
    }

    /**
     * @param PageMeta $meta
     * @return void
     */
    public function setMeta(PageMeta $meta): void
    {
        $this->meta = $meta;
    }
}