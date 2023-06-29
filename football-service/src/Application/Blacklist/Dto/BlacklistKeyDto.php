<?php

namespace Sportal\FootballApi\Application\Blacklist\Dto;

use JsonSerializable;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class BlacklistKeyDto implements JsonSerializable
{
    /**
     * @var string
     * @SWG\Property(enum=BLACKLIST_TYPE)
     */
    private $type;

    /**
     * @var string
     * @SWG\Property(enum=BLACKLIST_ENTITY_NAME)
     */
    private $entity;

    /**
     * @var string
     * @SWG\Property()
     */
    private $entity_id;

    /**
     * @var string
     * @SWG\Property()
     */
    private $context;

    /**
     * BlacklistKeyDto constructor.
     * @param $type
     * @param $entity
     * @param $entityId
     * @param $context
     */
    public function __construct($type = null, $entity = null, $entityId = null, $context = null)
    {
        $this->type = $type;
        $this->entity = $entity;
        $this->entity_id = $entityId;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entity_id;
    }

    /**
     * @return string
     */
    public function getContext(): ?string
    {
        return $this->context;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('type', new NotBlank());
        $metadata->addPropertyConstraint('type', new Assert\Choice([
            'choices' => BlacklistType::keys(),
            'message' => 'Choose a valid type. Options are: ' . implode(", ", BlacklistType::keys()),
        ]));

        $metadata->addPropertyConstraint('entity', new NotBlank());
        $metadata->addPropertyConstraint('entity', new Assert\Choice([
            'choices' => BlacklistEntityName::keys(),
            'message' => 'Choose a valid entity. Options are: ' . implode(", ", BlacklistEntityName::keys()),
        ]));

        $metadata->addPropertyConstraint('entity_id', new NotBlank());
    }

    /**
     * @param BlacklistKeyDto $dto
     * @return IBlacklistKey
     */
    public static function toBlacklistKey($dto)
    {
        return new BlacklistKey(
            BlacklistType::{$dto->getType()}(),
            BlacklistEntityName::{$dto->getEntity()}(),
            $dto->getEntityId(),
            $dto->getContext(),
        );
    }

}