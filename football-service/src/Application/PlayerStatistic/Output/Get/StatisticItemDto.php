<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Get;


use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_StatisticItem")
 */
class StatisticItemDto implements IDto, \JsonSerializable
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
     * StatisticItemDto constructor.
     * @param string $name
     * @param string|null $value
     */
    public function __construct(string $name, ?string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}