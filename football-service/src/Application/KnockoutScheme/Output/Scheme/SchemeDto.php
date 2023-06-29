<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Scheme;

use Sportal\FootballApi\Application\KnockoutScheme\Output\EdgeRound\EdgeRoundDto;
use Sportal\FootballApi\Application\KnockoutScheme\Output\Round\RoundDto;
use Sportal\FootballApi\Application\Stage\Output\Get\Dto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_KnockoutScheme")
 */
class SchemeDto implements \JsonSerializable
{

    /**
     * @SWG\Property(property="start_round")
     * @var EdgeRoundDto
     */
    private EdgeRoundDto $start_round;

    /**
     * @SWG\Property(property="end_round")
     * @var EdgeRoundDto
     */
    private EdgeRoundDto $end_round;

    /**
     * @SWG\Property(property="small_final")
     * @var bool|null
     */
    private ?bool $small_final;

    /**
     * @SWG\Property(property="stage")
     * @var \Sportal\FootballApi\Application\Stage\Output\Get\Dto
     */
    private Dto $stage;

    /**
     * @SWG\Property(property="rounds")
     * @var RoundDto[]
     */
    private array $rounds;

    /**
     * SchemeDto constructor.
     * @param EdgeRoundDto $start_round
     * @param EdgeRoundDto $end_round
     * @param bool|null $small_final
     * @param Dto $stage
     * @param RoundDto[] $rounds
     */
    public function __construct(EdgeRoundDto $start_round,
                                EdgeRoundDto $end_round,
                                ?bool $small_final,
                                Dto $stage,
                                array $rounds)
    {
        $this->start_round = $start_round;
        $this->end_round = $end_round;
        $this->small_final = $small_final;
        $this->stage = $stage;
        $this->rounds = $rounds;
    }

    /**
     * @return EdgeRoundDto
     */
    public function getStartRound(): EdgeRoundDto
    {
        return $this->start_round;
    }

    /**
     * @return EdgeRoundDto
     */
    public function getEndRound(): EdgeRoundDto
    {
        return $this->end_round;
    }

    /**
     * @return bool|null
     */
    public function getSmallFinal(): ?bool
    {
        return $this->small_final;
    }

    /**
     * @return Dto
     */
    public function getStage(): Dto
    {
        return $this->stage;
    }

    /**
     * @return RoundDto[]
     */
    public function getRounds(): array
    {
        return $this->rounds;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}