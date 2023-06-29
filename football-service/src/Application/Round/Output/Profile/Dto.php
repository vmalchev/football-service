<?php

namespace Sportal\FootballApi\Application\Round\Output\Profile;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_RoundWithStatus")
 */
class Dto extends \Sportal\FootballApi\Application\Round\Output\Partial\Dto
{

    /**
     * @SWG\Property(property="start_date")
     * @var string|null
     */
    protected ?string $start_date;

    /**
     * @SWG\Property(property="end_date")
     * @var string|null
     */
    protected ?string $end_date;

    /**
     * @SWG\Property(property="status", description="Not available at endpoint /v2/rounds")
     * @var string|null
     */
    private ?string $status;

    /**
     * @param string $id
     * @param string $key
     * @param string $name
     * @param string|null $type
     * @param string|null $start_date
     * @param string|null $end_date
     * @param string|null $status
     */
    public function __construct(string $id, string $key, string $name, ?string $type,
                                ?string $start_date, ?string $end_date, ?string $status)
    {
        parent::__construct($id, $key, $name, $type);

        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->status = $status;
    }

    /**
     * @SWG\Property(property="id")
     * @var string
     */

    /**
     * @SWG\Property(property="key")
     * @var string
     */

    /**
     * @SWG\Property(property="name")
     * @var string
     */

    /**
     * @SWG\Property(property="type")
     * @var string|null
     */

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
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $serialized = get_object_vars($this);
        if (is_null($this->status)) {
            unset($serialized['status']);
        }

        return $serialized;
    }

}