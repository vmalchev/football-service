<?php
namespace Sportal\FootballApi\Dto\Statistic\Team;


use Sportal\FootballApi\Application\IDto;

class InputDto implements IDto
{
    public $teamIds;

    public $seasonIds;

    /**
     * @var string
     */
    public $languageCode;

}
