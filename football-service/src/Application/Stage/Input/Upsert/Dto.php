<?php


namespace Sportal\FootballApi\Application\Stage\Input\Upsert;


use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\Stage\StageType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_SeasonStage")
 */
class Dto implements \JsonSerializable, ITranslatable, IDto
{
    /**
     * @SWG\Property(property="id")
     * @var string|null
     */
    private ?string $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    /**
     * @SWG\Property(property="type", enum=STAGE_TYPE)
     * @var string
     */
    private string $type;

    /**
     * @SWG\Property(property="start_date", format="date")
     * @var string|null
     */
    private ?string $start_date;

    /**
     * @SWG\Property(property="end_date", format="date")
     * @var string|null
     */
    private ?string $end_date;

    /**
     * @SWG\Property(property="order_in_season")
     * @var int
     */
    private int $order_in_season;

    /**
     * @SWG\Property(property="coverage")
     * @var string|null
     */
    private ?string $coverage;

    /**
     * @param string|null $id
     * @param string $name
     * @param string $type
     * @param string|null $start_date
     * @param string|null $end_date
     * @param int $order_in_season
     * @param string|null $coverage
     */
    public function __construct(?string $id,
                                string $name,
                                string $type,
                                ?string $start_date,
                                ?string $end_date,
                                int $order_in_season,
                                ?string $coverage)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->order_in_season = $order_in_season;
        $this->coverage = $coverage;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    /**
     * @return int
     */
    public function getOrderInSeason(): int
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
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::STAGE();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'id' => [
                    new Assert\NotBlank(['allowNull' => true]),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'type' => [
                    new Assert\NotBlank(),
                    new Assert\Choice(
                        [
                            'choices' => array_values(StageType::toArray()),
                            'message' => 'Choose a valid stage type. Options are: ' . implode(", ", StageType::toArray()),
                        ]
                    ),
                ],
                'start_date' => [
                    new Assert\Date()
                ],
                'end_date' => [
                    new Assert\Date()
                ],
                'name' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => ['string']])
                ],
                'order_in_season' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                    new Assert\Type(['type' => ['int']])
                ],
                'coverage' => [
                    new Assert\Optional(
                        new Assert\Choice(
                            [
                            'choices' => array_values(MatchCoverage::keys()),
                            'message' => 'Choose a valid stage type. Options are: ' . implode(", ", MatchCoverage::keys()),
                            ]
                        )
                    ),
                ],
            ]
        );
    }

}