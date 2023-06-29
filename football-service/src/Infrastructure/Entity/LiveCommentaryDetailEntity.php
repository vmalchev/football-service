<?php

namespace Sportal\FootballApi\Infrastructure\Entity;

use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryDetailEntity;

class LiveCommentaryDetailEntity implements ILiveCommentaryDetailEntity, \JsonSerializable
{
    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $value;

    /**
     * @var array
     */
    public $data;

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @var array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}