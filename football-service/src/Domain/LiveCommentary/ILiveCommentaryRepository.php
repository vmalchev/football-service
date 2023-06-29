<?php
namespace Sportal\FootballApi\Domain\LiveCommentary;


use Sportal\FootballApi\Domain\LiveCommentary\Specification\MatchSpecification;

interface ILiveCommentaryRepository
{
    /**
     * @param int $matchId
     * @param string $languageCode
     * @return ILiveCommentaryEntity[]
     */
    public function findByMatchIdAndLanguageCode(int $matchId, string $languageCode): array;
}