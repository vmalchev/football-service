<?php
namespace Sportal\FootballApi\Domain\LiveCommentary;


interface ILiveCommentaryEntity
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return int
     */
    public function getElapsed();

    /**
     * @return string
     */
    public function getTemplateText();

    /**
     * @return ILiveCommentaryDetailEntity[]
     */
    public function getDetails();

    /**
     * @return mixed
     */
    public function getIncidentTimestamp();
}