<?php


namespace Sportal\FootballApi\Application\Stage\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchStage")
 */
class Dto implements JsonSerializable, ITranslatable
{
    /**
     * @SWG\Property(property="id")
     * @var string
     */
    protected string $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */

    protected string $name;

    /**
     * @SWG\Property(property="type", enum=STAGE_TYPE)
     * @var string|null
     */
    protected ?string $type;

    /**
     * @SWG\Property(property="start_date", format="date")
     * @var string|null
     */
    protected ?string $start_date;

    /**
     * @SWG\Property(property="end_date", format="date")
     * @var string|null
     */
    protected ?string $end_date;

    /**
     * @SWG\Property(property="order_in_season")
     * @var int|null
     */
    protected ?int $order_in_season;

    /**
     * @SWG\Property(property="coverage")
     * @var string|null
     */
    protected ?string $coverage;

    /**
     * @SWG\Property(property="status", enum=STAGE_STATUS, description="Available only for /v2/seasons/{id}/stages")
     * @var string|null
     */
    protected ?string $status;

    /**
     * StageDto constructor.
     * @param string $id
     * @param string $name
     * @param string|null $type
     * @param string|null $start_date
     * @param string|null $end_date
     * @param int|null $order_in_season
     * @param string|null $coverage
     * @param string|null $status
     */
    public function __construct(string $id, string $name, ?string $type, ?string $start_date, ?string $end_date, ?int $order_in_season,
                                ?string $coverage, ?string $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->order_in_season = $order_in_season;
        $this->coverage = $coverage;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getId(): string
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
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    /**
     * @return string
     */
    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    /**
     * @return int|null
     */
    public function getOrderInSeason(): ?int
    {
        return $this->order_in_season;
    }

    /**
     * @return string|null
     */
    public function getCoverage(): ?string
    {
        return $this->coverage;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $serialized = get_object_vars($this);
        if (is_null($this->status)) {
            unset($serialized['status']);
        }

        return $serialized;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::STAGE();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}