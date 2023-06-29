<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Input\Upsert;

use Sportal\FootballApi\Domain\PlayerStatistic\PlayerStatisticType;
use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="PlayerStatisticItem")
 */
class StatisticItem
{
    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    /**
     * @SWG\Property(property="value")
     * @var string|null
     */
    private ?string $value;

    /**
     * @param string $name
     * @param string|null $value
     */
    public function __construct(string $name, ?string $value)
    {
        $this->name = $name;
        $this->value = $value;
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
    public function getValue(): ?string
    {
        return $this->value;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'name' => new Assert\Choice(
                    [
                        'choices' => PlayerStatisticType::toArray(),
                        'message' => 'Choose a valid statistic type. Options are: ' . implode(", ", PlayerStatisticType::toArray()),
                    ]
                ),
                'value' => new Assert\Required()
            ]
        );
    }
}