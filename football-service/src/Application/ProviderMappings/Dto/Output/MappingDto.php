<?php


namespace Sportal\FootballApi\Application\ProviderMappings\Dto\Output;


/**
 * Class Dto
 * @package Sportal\FootballApi\Application\ProviderMappings\Dto\Output\Mapping
 *
 * @SWG\Definition(definition="v2_MappingDto")
 */
class MappingDto implements \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="provider")
     */
    private $provider;

    /**
     * @var string
     * @SWG\Property(property="entity_type", enum=ENTITY_TYPE, type="string")
     */
    private $entity_type;

    /**
     * @var string|null
     * @SWG\Property(property="domain_id")
     */
    private $domain_id;

    /**
     * @var string
     * @SWG\Property(property="provider_id")
     */
    private $provider_id;

    /**
     * Dto constructor.
     * @param string $provider
     * @param string $entity_type
     * @param string|null $domain_id
     * @param string $provider_id
     */
    public function __construct(string $provider, string $entity_type, ?string $domain_id, string $provider_id)
    {
        $this->provider = $provider;
        $this->entity_type = $entity_type;
        $this->domain_id = $domain_id;
        $this->provider_id = $provider_id;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entity_type;
    }

    /**
     * @return string|null
     */
    public function getDomainId(): ?string
    {
        return $this->domain_id;
    }

    /**
     * @return string
     */
    public function getProviderId(): string
    {
        return $this->provider_id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}