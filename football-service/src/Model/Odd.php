<?php
namespace Sportal\FootballApi\Model;

use Sportal\FOotballApi\Domain\Odd\IApplicationLink;

/**
 * Odd
 * @SWG\Definition(required={"event_id", "odd_provider"})
 */
class Odd implements ModelInterface, \JsonSerializable
{

    const PRIMARY_KEYS = [
        'event_id',
        'odd_provider_id'
    ];

    const TYPES = ['decimal', 'fractional', 'moneyline'];


    /**
     * Reference to the OddProvider which provides the betting offers
     * @var \Sportal\FootballApi\Model\OddProvider
     * @SWG\Property()
     */
    private $oddProvider;

    /**
     * Id of the event for which odds are provided
     * @var integer
     * @SWG\Property()
     */
    private $eventId;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $reference;

    /**
     *
     * @var array
     */
    private $linkedData;

    private ?int $sortOrder = null;

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    /**
     * @param int|null $sortOrder
     * @return Odd
     */
    public function setSortOrder(?int $sortOrder): Odd
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Odd
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Odd
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return Odd
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        $data = array_merge($this->getPrimaryKeyMap(), [
            'source' => $this->source,
            'reference' => $this->reference,
            'data' => json_encode($this->data)
        ]);

        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'event_id' => $this->eventId,
            'odd_provider_id' => $this->oddProvider->getId()
        ];
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\OddProvider
     */
    public function getOddProvider()
    {
        return $this->oddProvider;
    }

    /**
     *
     * @param \Sportal\FootballApi\Model\OddProvider $oddProvider
     * @return \Sportal\FootballApi\Model\Odd
     */
    public function setOddProvider(\Sportal\FootballApi\Model\OddProvider $oddProvider)
    {
        $this->oddProvider = $oddProvider;
        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     *
     * @param integer $eventId
     * @return \Sportal\FootballApi\Model\Odd
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
        return $this;
    }

    public function jsonSerialize()
    {
        $json = [
            'event_id' => $this->eventId,
            'odd_provider' => $this->oddProvider
        ];

        if (empty($this->linkedData)) {
            return array_merge($json, $this->data);
        } else {
            return array_merge($json, $this->linkedData);
        }
    }

    /**
     *
     * @param IApplicationLink[] $applicationLinks
     */
    public function setLinks(array $applicationLinks)
    {
        $this->oddProvider->setLinks($applicationLinks);
        $this->linkedData = [];
        foreach ($this->data as $key => $type) {
            foreach ($type as $subtype => $value) {
                $links = [];
                foreach ($applicationLinks as $applicationLink) {
                    $link = $applicationLink->getFallbackLink();
                    if (! empty($applicationLink->getBetslipLink())) {
                        if (! empty($value['provider_info'])) {
                            $keys = array_map(fn($key) => "$" . $key . "$", array_keys($value['provider_info']));
                            $values = array_map('urlencode', array_values($value['provider_info']));
                            $link = str_replace($keys, $values, $applicationLink->getBetslipLink());
                        } elseif (! empty($value['coupon']) && ! empty($applicationLink->getBetslipLink())) {
                            $link = str_replace('$coupon$', $value['coupon'], $applicationLink->getBetslipLink());
                        }
                    }
                    $links[] = [
                        'application' => $applicationLink->getApplicationId(),
                        'link' => $link
                    ];
                }

                if (! empty($links)) {
                    $value['links'] = $links;
                    if (count($links) == 1) {
                        $value['url'] = $links[0]['link'];
                    }
                }
                $this->linkedData[$key][$subtype] = $value;
            }
        }
    }
}

