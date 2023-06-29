<?php


namespace Sportal\FootballApi\Application\ProviderMappings\Dto\Input;


use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Dto
 * @package Sportal\FootballApi\Application\ProviderMappings\Dto\Input
 *
 * @SWG\Definition(definition="v2_MappingRequestsDto", required={"provider_id", "mapping_requests"})
 */
class Dto implements \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="provider")
     */
    private string $provider;

    /**
     * @var MappingRequestDto[]
     * @SWG\Property(property="mapping_requests")
     */
    private array $mapping_requests;

    /**
     * Dto constructor.
     * @param string $provider
     * @param MappingRequestDto[] $mapping_requests
     */
    public function __construct(string $provider, array $mapping_requests)
    {
        $this->provider = $provider;
        $this->mapping_requests = $mapping_requests;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
           'provider' => new Assert\Required([
               new Assert\Type('string'),
               new Assert\Choice([
                   'choices' => array_values(array('ENETPULSE')),
                   'message' => 'The only supported value as of now is ENETPULSE.'
                   ]
               )
           ]),
            'mapping_requests' => new Assert\Required([
                new Assert\Type('array'),
                new Assert\NotBlank(),
                new Assert\All([
                    MappingRequestDto::getValidatorConstraints()
                ])
            ])
        ]);
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return strtolower($this->provider);
    }

    /**
     * @return MappingRequestDto[]
     */
    public function getMappingRequests(): array
    {
        return $this->mapping_requests;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}