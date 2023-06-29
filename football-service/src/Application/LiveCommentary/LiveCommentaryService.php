<?php
namespace Sportal\FootballApi\Application\LiveCommentary;

use Sportal\FootballApi\Application\Mapper\LiveCommentaryOutputMapper;
use Sportal\FootballApi\Domain\LiveCommentary\LiveCommentaryCollection;

final class LiveCommentaryService implements \Sportal\FootballApi\Application\IService
{
    /**
     * @var LiveCommentaryCollection
     */
    private $liveCommentaryCollection;

    /**
     * @var LiveCommentaryOutputMapper
     */
    private $liveCommentaryOutputMapper;

    public function __construct(
        LiveCommentaryCollection $liveCommentaryCollection,
        LiveCommentaryOutputMapper $liveCommentaryOutputMapper
    ) {
        $this->liveCommentaryCollection = $liveCommentaryCollection;
        $this->liveCommentaryOutputMapper  = $liveCommentaryOutputMapper;
    }

    public function process(\Sportal\FootballApi\Application\IDto $dto)
    {
        try {
            $liveCommentaries = $this->liveCommentaryCollection->getByMatch($dto->matchId, $dto->languageCode);

        } catch(\Exception $exception) {
            throw $exception;
        }

        $output = $this->liveCommentaryOutputMapper->mapMultiple($liveCommentaries);
        return $output;
    }
}