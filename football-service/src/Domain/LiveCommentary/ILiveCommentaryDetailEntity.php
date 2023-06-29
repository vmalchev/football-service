<?php


namespace Sportal\FootballApi\Domain\LiveCommentary;


interface ILiveCommentaryDetailEntity
{
    /**
     * @return string
     */
    public function getPlaceholder();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return array
     */
    public function getData();
}