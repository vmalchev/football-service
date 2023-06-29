<?php

namespace Sportal\FootballApi\Domain\LiveCommentary;


use Sportal\FootballApi\Domain\ILanguageRepository;
use Sportal\FootballApi\Domain\LiveCommentary\Exception\InvalidLiveCommentaryInputException;
use Sportal\FootballApi\Domain\Match\IMatchRepository;

class LiveCommentarySpecification
{
    /**
     * @var IMatchRepository
     */
    private $matchRepository;

    /**
     * @var ILanguageRepository
     */
    private $languageRepository;

    public function __construct(
        IMatchRepository $matchRepository,
        ILanguageRepository $languageRepository
    )
    {
        $this->matchRepository = $matchRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param int $matchId
     * @param string $languageCode
     * @throws InvalidMatchException
     */
    public function process($matchId, $languageCode)
    {
        if (!($this->matchRepository->existsById($matchId))) {
            throw new InvalidLiveCommentaryInputException('Invalid match identifier');
        }

        if (!in_array($languageCode, $this->languageRepository->getLanguageCodes())) {
            throw new InvalidLiveCommentaryInputException('Invalid language code');
        }
    }
}