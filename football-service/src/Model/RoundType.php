<?php


namespace Sportal\FootballApi\Model;


class RoundType implements \JsonSerializable, Translateable, MlContainerInterface
{

    use ContainsMlContent;

    /**
     * Unique identifier of the round type
     * @var integer
     */
    private $id;

    /**
     * Human readable name describing the round type, can be translated
     * @var string
     */
    private $name;

    /**
     * RoundType constructor.
     * @param int|null $id
     * @param string|null $name
     */
    public function __construct(?int $id, ?string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }


    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name
        ];

        return $this->translateContent($data);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        return $this->id == null ? [] : [$this];
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

}