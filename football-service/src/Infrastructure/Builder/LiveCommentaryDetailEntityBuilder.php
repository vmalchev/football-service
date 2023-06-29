<?php

namespace Sportal\FootballApi\Infrastructure\Builder;

use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryDetailData;
use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryDetailEntity;
use Sportal\FootballApi\Infrastructure\Entity\LiveCommentaryDetailEntity;


class LiveCommentaryDetailEntityBuilder
{
    /**
     * @var ILiveCommentaryDetailEntity
     */
    private $liveCommentaryDetailEntity;

    public function __construct(
        ILiveCommentaryDetailEntity $liveCommentaryDetailEntity
    ) {
        $this->liveCommentaryDetailEntity = $liveCommentaryDetailEntity;
    }

    public function create(array $properties): ILiveCommentaryDetailEntity
    {
        $liveCommentaryDetailEntity = clone $this->liveCommentaryDetailEntity;

        $data = $properties['data'] ?? null;
        
        foreach($properties as $key => $value) {
            if (property_exists(LiveCommentaryDetailEntity::class, $key)) {
                if ($key == 'value' && $data !== null && $data instanceof ILiveCommentaryDetailData) {
                    $liveCommentaryDetailEntity->value = $data->getPlaceholderValue();
                } else {
                    $liveCommentaryDetailEntity->{$key} = $value;
                }
            }
        }

        return $liveCommentaryDetailEntity;
    }
}
