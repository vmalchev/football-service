<?php


namespace Sportal\FootballApi\Application\Search\Dto;


class QueryDto
{
    /**
     * @var
     */
    public $entityTypes;

    /**
     * @var
     */
    public $languageCodes;

    /**
     * @var array
     */
    public $query;

    /**
     * @var
     */
    public $hierarchicalScope;

    /**
     * @var boolean
     */
    public $isOriginIncluded;
}