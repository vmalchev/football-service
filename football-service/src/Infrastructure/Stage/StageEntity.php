<?php


namespace Sportal\FootballApi\Infrastructure\Stage;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Stage\StageStatus;
use Sportal\FootballApi\Domain\Stage\StageType;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Match\Converter\MatchCoverageConverter;

class StageEntity extends GeneratedIdDatabaseEntity implements IStageEntity, IDatabaseEntity
{
    private ?string $id;
    private string $name;
    private string $seasonId;
    private ?ISeasonEntity $season;
    private ?StageType $type;
    private ?DateTimeInterface $startDate;
    private ?DateTimeInterface $endDate;
    private ?string $confederation;
    private ?int $orderInSeason;
    private ?MatchCoverage $coverage;
    private ?StageStatus $stageStatus;

    /**
     * StageEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param string $seasonId
     * @param ISeasonEntity|null $season
     * @param StageType|null $type
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     * @param string|null $confederation
     * @param int|null $orderInSeason
     * @param MatchCoverage|null $coverage
     * @param StageStatus|null $stageStatus
     */
    public function __construct(?string $id,
                                string $name,
                                string $seasonId,
                                ?ISeasonEntity $season,
                                ?StageType $type,
                                ?DateTimeInterface $startDate,
                                ?DateTimeInterface $endDate,
                                ?string $confederation,
                                ?int $orderInSeason,
                                ?MatchCoverage $coverage,
                                ?StageStatus $stageStatus)
    {
        $this->id = $id;
        $this->name = $name;
        $this->seasonId = $seasonId;
        $this->season = $season;
        $this->type = $type;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->confederation = $confederation;
        $this->orderInSeason = $orderInSeason;
        $this->coverage = $coverage;
        $this->stageStatus = $stageStatus;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSeasonId(): string
    {
        return $this->seasonId;
    }

    /**
     * @return ISeasonEntity|null
     */
    public function getSeason(): ?ISeasonEntity
    {
        return $this->season;
    }

    /**
     * @return StageType|null
     */
    public function getType(): ?StageType
    {
        return $this->type;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @return string|null
     */
    public function getConfederation(): ?string
    {
        return $this->confederation;
    }

    /**
     * @return int|null
     */
    public function getOrderInSeason(): ?int
    {
        return $this->orderInSeason;
    }

    /**
     * @return MatchCoverage|null
     */
    public function getCoverage(): ?MatchCoverage
    {
        return $this->coverage;
    }

    /**
     * @return StageStatus|null
     */
    public function getStageStatus(): ?StageStatus
    {
        return $this->stageStatus;
    }

    public function withId(string $id): StageEntity
    {
        $entity = clone $this;
        $entity->id = $id;
        return $entity;
    }

    public function getDatabaseEntry(): array
    {
        return [
            StageTableMapper::FIELD_NAME => $this->name,
            StageTableMapper::FIELD_SEASON_ID => $this->seasonId,
            StageTableMapper::FIELD_START_DATE => $this->startDate,
            StageTableMapper::FIELD_END_DATE => $this->endDate,
            StageTableMapper::FIELD_UPDATED_AT => (new \DateTime())->format(\DateTimeInterface::ATOM),
            StageTableMapper::FIELD_LIVE => MatchCoverageConverter::toValue($this->coverage),
            StageTableMapper::FIELD_ORDER_IN_SEASON => $this->orderInSeason,
            StageTableMapper::FIELD_TYPE => $this->type !== null ? $this->type->getValue() : null,
            StageTableMapper::FIELD_CUP => StageType::KNOCK_OUT()->equals($this->type),
            StageTableMapper::FIELD_COUNTRY_ID => $this->season !== null && $this->season->getTournament() !== null ?
                $this->season->getTournament()->getCountryId() : null
        ];
    }

    public function setStageStatus(StageStatus $stageStatus): void
    {
        $this->stageStatus = $stageStatus;
    }


}