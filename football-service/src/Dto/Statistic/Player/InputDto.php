<?php

namespace Sportal\FootballApi\Dto\Statistic\Player;


use Sportal\FootballApi\Dto\IDto;

class InputDto implements IDto
{
    public $playerIds;

    public $seasonIds;

    public $teamId;

    /**
     * @var string
     */
    public $languageCode;
}
