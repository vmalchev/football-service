<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\EdgeRound;


use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_KnockoutEdgeRound")
 */
class EdgeRoundDto implements \JsonSerializable
{

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    /**
     * EdgeRoundDto constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}