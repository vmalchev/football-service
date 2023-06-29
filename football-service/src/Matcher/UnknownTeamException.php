<?php
namespace Sportal\FootballApi\Matcher;

class UnknownTeamException extends \Exception
{

    private $source;

    private $teamIds;

    public function __construct($source, array $teamIds)
    {
        $message = "Unable to find teams (" . implode(',', $teamIds) . ") from " . $source;
        parent::__construct($message);
        $this->source = $source;
        $this->teamIds = $teamIds;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getTeamIds()
    {
        return $this->teamIds;
    }
}