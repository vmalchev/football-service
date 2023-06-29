<?php

namespace Sportal\FootballApi\Application\Mapper;

use DateTimeInterface;
use Sportal\FootballApi\Application\LiveCommentary\Dto\LiveCommentaryOutputDto;
use Sportal\FootballApi\Domain\LiveCommentary\LiveCommentaryModel;

class LiveCommentaryOutputMapper
{
    /**
     * @param LiveCommentaryModel[] $liveCommentaryCollection
     */
    public function mapMultiple(array $liveCommentaryCollection)
    {
        return array_map(function ($liveCommentary) {
            $details = (array)$liveCommentary->getEntity()->getDetails();

            return new LiveCommentaryOutputDto(
                $liveCommentary->getEntity()->getType(),
                $liveCommentary->getEntity()->getTemplateText(),
                $liveCommentary->generateAutoText(),
                $liveCommentary->getEntity()->getElapsed(),
                $details,
                !is_null($liveCommentary->getEntity()->getIncidentTimestamp()) ?
                    $liveCommentary->getEntity()->getIncidentTimestamp()->format(DateTimeInterface::ATOM) : null
            );
        }, $liveCommentaryCollection);
    }
}