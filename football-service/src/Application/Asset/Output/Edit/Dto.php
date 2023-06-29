<?php


namespace Sportal\FootballApi\Application\Asset\Output\Edit;


use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Asset")
 */
class Dto implements \JsonSerializable, IDto
{
    /**
     * @var string
     * @SWG\Property(property="entity")
     */
    private string $entity;

    /**
     * @var string
     * @SWG\Property(property="entity_id")
     */
    private string $entity_id;

    /**
     * @var string
     * @SWG\Property(property="type")
     */
    private string $type;

    /**
     * @var string
     * @SWG\Property(property="path")
     */
    private string $path;

    /**
     * @var string|null
     * @SWG\Property(property="context_type")
     */
    private ?string $context_type;

    /**
     * @var string|null
     * @SWG\Property(property="context_id")
     */
    private ?string $context_id;

    /**
     * Dto constructor.
     * @param string $entity
     * @param string $entity_id
     * @param string $type
     * @param string $path
     * @param string|null $context_type
     * @param string|null $context_id
     */
    public function __construct(string $entity, string $entity_id, string $type, string $path, ?string $context_type = null, ?string $context_id = null)
    {
        $this->entity = $entity;
        $this->entity_id = $entity_id;
        $this->type = $type;
        $this->path = $path;
        $this->context_type = $context_type;
        $this->context_id = $context_id;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getContextType(): ?string
    {
        return $this->context_type;
    }

    /**
     * @return string|null
     */
    public function getContextId(): ?string
    {
        return $this->context_id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}