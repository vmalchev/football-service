<?php

namespace Sportal\FootballApi\Application\LiveCommentary\Dto;

/**
 * @SWG\Definition(definition="LiveCommentaryOutputDto")
 */
class LiveCommentaryOutputDto implements \JsonSerializable
{
    /**
     * @SWG\Property(property="type")
     * @var string|null
     */
    public $type;

    /**
     * @SWG\Property(property="template_text")
     * @var string|null
     */
    public $template_text;

    /**
     * @SWG\Property(property="auto_text")
     * @var string|null
     */
    public $auto_text;

    /**
     * @SWG\Property(property="elapsed")
     * @var int|null
     */
    public $elapsed;

    /**
     * @SWG\Property(property="details")
     * @var object[]
     */
    public $details;

    /**
     * @SWG\Property(property="incident_timestamp")
     * @var DateTime|null
     */
    public $incident_timestamp;


    /**
     * LiveCommentaryOutputDto constructor.
     * @param string|null $type
     * @param string|null $templateText
     * @param string|null $elapsed
     * @param array|null $details
     * @param DateTime|null $incidentTimestamp
     */
    public function __construct(
        $type,
        $templateText,
        $autoText,
        $elapsed,
        array $details,
        $incidentTimestamp
    ) {
        $this->type = $type;
        $this->template_text = $templateText;
        $this->auto_text = $autoText;
        $this->elapsed = $elapsed;
        $this->details = $details;
        $this->incident_timestamp = $incidentTimestamp;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}