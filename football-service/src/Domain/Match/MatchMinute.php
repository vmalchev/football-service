<?php


namespace Sportal\FootballApi\Domain\Match;


class MatchMinute
{
    private int $regular;

    private ?int $injury;

    /**
     * MatchMinute constructor.
     * @param int $regular
     * @param int|null $injury
     */
    public function __construct(int $regular, ?int $injury)
    {
        $this->regular = $regular;
        $this->injury = $injury;
    }

    /**
     * @return int
     */
    public function getRegular(): int
    {
        return $this->regular;
    }

    /**
     * @return int|null
     */
    public function getInjury(): ?int
    {
        return $this->injury;
    }
}