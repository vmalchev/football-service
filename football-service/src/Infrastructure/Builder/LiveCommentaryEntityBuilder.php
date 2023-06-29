<?php

namespace Sportal\FootballApi\Infrastructure\Builder;

use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryEntity;
use Sportal\FootballApi\Infrastructure\Entity\LiveCommentaryEntity;

class LiveCommentaryEntityBuilder
{
    /**
     * @var ILiveCommentaryEntity
     */
    private $liveCommentaryEntity;

    /**
     * @var LiveCommentaryDetailEntityBuilder
     */
    private $liveCommentaryDetailEntityBuilder;

    public function __construct(
        ILiveCommentaryEntity $liveCommentaryEntity,
        LiveCommentaryDetailEntityBuilder $liveCommentaryDetailEntityBuilder
    ) {
        $this->liveCommentaryEntity = $liveCommentaryEntity;
        $this->liveCommentaryDetailEntityBuilder = $liveCommentaryDetailEntityBuilder;
    }

    public function create(array $properties): ILiveCommentaryEntity
    {
        $liveCommentaryEntity = clone $this->liveCommentaryEntity;

        foreach ($properties as $key => $value) {
            if (property_exists(LiveCommentaryEntity::class, $key)) {
                if (is_array($value)) {
                    $liveCommentaryEntity->details = [];
                    foreach ($value as $detail) {
                        $liveCommentaryEntity->details[] = $this->liveCommentaryDetailEntityBuilder->create($detail);
                    }
                } else {
                    $liveCommentaryEntity->{$key} = $value;
                }
            }
        }

        return $liveCommentaryEntity;
    }
}
