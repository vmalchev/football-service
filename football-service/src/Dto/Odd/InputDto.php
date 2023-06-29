<?php
namespace Sportal\FootballApi\Dto\Odd;


use Sportal\FootballApi\Dto\IDto;
use Sportal\FootballApi\Model\Enum\OddFormat;

class InputDto implements IDto
{
    private $eventIds;
    private $oddClientCode;

    /**
     * @var OddFormat
     */
    private $oddFormat;

    /**
     * @var OddsType
     */
    private $oddsType;

    /**
     * @param array $eventIds
     * @param $oddClientCode
     * @param OddFormat $oddFormat
     * @param OddsType $oddsType
     */
    public function __construct(array $eventIds, $oddClientCode, OddFormat $oddFormat, OddsType $oddsType) {
        $this->eventIds = $eventIds;
        $this->oddClientCode = $oddClientCode;
        $this->oddFormat = $oddFormat;
        $this->oddsType = $oddsType;
    }

    /**
     * @return mixed
     */
    public function getEventIds()
    {
        return $this->eventIds;
    }

    /**
     * @return string
     */
    public function getOddClientCode()
    {
        return $this->oddClientCode;
    }

    /**
     * @return mixed
     */
    public function getOddFormat()
    {
        return $this->oddFormat;
    }

    /**
     * @return OddsType
     */
    public function getOddsType(): OddsType
    {
        return $this->oddsType;
    }

}