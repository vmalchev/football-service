<?php


namespace Sportal\FootballApi\Infrastructure\Stage;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntityFactory;
use Sportal\FootballApi\Domain\Stage\StageStatus;
use Sportal\FootballApi\Domain\Stage\StageType;

class StageEntityFactory implements IStageEntityFactory
{
    private ?string $id = null;
    private string $name;
    private string $seasonId;
    private ?ISeasonEntity $season = null;
    private ?StageType $type;
    private ?DateTimeInterface $startDate = null;
    private ?DateTimeInterface $endDate = null;
    private ?string $confederation = null;
    private ?MatchCoverage $coverage = null;
    private ?int $orderInSeason = null;
    private ?StageStatus $stageStatus = null;

    /**
     * @param string|null $id
     * @return StageEntityFactory
     */
    public function setId(?string $id): StageEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return StageEntityFactory
     */
    public function setName(string $name): StageEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $seasonId
     * @return StageEntityFactory
     */
    public function setSeasonId(string $seasonId): StageEntityFactory
    {
        $this->seasonId = $seasonId;
        return $this;
    }

    /**
     * @param ISeasonEntity|null $season
     * @return StageEntityFactory
     */
    public function setSeason(?ISeasonEntity $season): StageEntityFactory
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @param StageType|null $type
     * @return StageEntityFactory
     */
    public function setType(?StageType $type): StageEntityFactory
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $startDate
     * @return StageEntityFactory
     */
    public function setStartDate(?DateTimeInterface $startDate): StageEntityFactory
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $endDate
     * @return StageEntityFactory
     */
    public function setEndDate(?DateTimeInterface $endDate): StageEntityFactory
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @param string|null $confederation
     * @return StageEntityFactory
     */
    public function setConfederation(?string $confederation): StageEntityFactory
    {
        $this->confederation = $confederation;
        return $this;
    }


    public function setEmpty(): IStageEntityFactory
    {
        return new StageEntityFactory();
    }

    public function create(): IStageEntity
    {
        return new StageEntity(
            $this->id,
            $this->name,
            $this->seasonId,
            $this->season,
            $this->type,
            $this->startDate,
            $this->endDate,
            $this->confederation,
            $this->orderInSeason,
            $this->coverage,
            $this->stageStatus
        );
    }

    public function setFrom(IStageEntity $stage): IStageEntityFactory
    {
        $factory = new StageEntityFactory();
        $factory->id = $stage->getId();
        $factory->name = $stage->getName();
        $factory->seasonId = $stage->getSeasonId();
        $factory->season = $stage->getSeason();
        $factory->type = $stage->getType();
        $factory->startDate = $stage->getStartDate();
        $factory->endDate = $stage->getEndDate();
        $factory->confederation = $stage->getConfederation();
        $factory->stageStatus = $stage->getStageStatus();
        $factory->orderInSeason = $stage->getOrderInSeason();
        $factory->coverage = $stage->getCoverage();
        return $factory;
    }

    /**
     * @param MatchCoverage|null $coverage
     * @return IStageEntityFactory
     */
    public function setCoverage(?MatchCoverage $coverage): IStageEntityFactory
    {
        $this->coverage = $coverage;
        return $this;
    }

    /**
     * @param int|null $orderInSeason
     * @return IStageEntityFactory
     */
    public function setOrderInSeason(?int $orderInSeason): IStageEntityFactory
    {
        $this->orderInSeason = $orderInSeason;
        return $this;
    }

    /**
     * @param StageStatus|null $stageStatus
     * @return IStageEntityFactory
     */
    public function setStageStatus(?StageStatus $stageStatus): IStageEntityFactory
    {
        $this->stageStatus = $stageStatus;
        return $this;
    }
}