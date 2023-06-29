<?php

namespace Sportal\FootballApi\Domain\LiveCommentary;

class LiveCommentaryBuilder
{
    /**
     * @var LiveCommentaryModel
     */
    private $liveCommentary;

    public function __construct(
       LiveCommentaryModel $liveCommentary
    ) {
        $this->liveCommentary = $liveCommentary;
    }

    public function build(ILiveCommentaryEntity $entity): LiveCommentaryModel
    {
        $liveCommentary = clone $this->liveCommentary;
        $liveCommentary->setEntity($entity);

        return $liveCommentary;
    }
}