<?php


namespace Sportal\FootballApi\Application\Shared\Dto;


interface PageDto
{
    public function setPageMeta(PageMetaDto $pageMetaDto): PageDto;
}