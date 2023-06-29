<?php


namespace Sportal\FootballApi\Application\Search\Dto;


use Sportal\FootballApi\Application\IDto;

class InputDto implements IDto
{
    public $query;

    public $origin;

    public $entityTypes;

    public $languageCode;

    public $scope;
}