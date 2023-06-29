<?php
namespace Sportal\FootballApi\Domain\LiveCommentary;


interface ILiveCommentaryCollection
{
    /**
     * @return ILiveCommentaryModel[]
     */
    public function get(): array;

    /**
     * @param int $matchId
     * @param string $languageCode
     * @return ILiveCommentaryModel[]
     */
    public function getByMatch(int $matchId, string $languageCode): array;
}