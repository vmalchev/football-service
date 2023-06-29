<?php


namespace Sportal\FootballApi\Application\MatchEvent\Input\Put;


use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_MatchEventInputWrapper")
 */
class Dto implements IDto
{
    private ?string $matchId;

    /**
     * @SWG\Property(property="events")
     * @var MatchEventDto[]
     */
    private array $events;

    /**
     * Dto constructor.
     * @param string|null $matchId
     * @param array $events
     */
    public function __construct(?string $matchId = null, array $events)
    {
        $this->matchId = $matchId;
        $this->events = $events;
    }

    /**
     * @return string|null
     */
    public function getMatchId(): ?string
    {
        return $this->matchId;
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @param string|null $matchId
     * @return Dto
     */
    public function setMatchId(?string $matchId): Dto
    {
        $dto = clone $this;
        $dto->matchId = $matchId;
        return $dto;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'events' => new Assert\Required([
                new Assert\Type('array'),
                new Assert\All([MatchEventDto::getValidatorConstraints()])
            ])
        ]);
    }


}