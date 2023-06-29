<?php

namespace Sportal\FootballApi\Infrastructure\Entity;

use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryDetailEntity;
use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryEntity;

class LiveCommentaryEntity implements ILiveCommentaryEntity, \JsonSerializable
{

    public $type;
    public $elapsed;
    public $template_text;

    /**
     * @var ILiveCommentaryDetailEntity[]
     */
    public $details;
    public $incident_timestamp;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getElapsed()
    {
        return $this->elapsed;
    }

    /**
     * @return string
     */
    public function getTemplateText()
    {
        return $this->template_text;
    }

    /**
     * @return ILiveCommentaryDetailEntity[]
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return mixed
     */
    public function getIncidentTimestamp()
    {
        return $this->incident_timestamp;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}