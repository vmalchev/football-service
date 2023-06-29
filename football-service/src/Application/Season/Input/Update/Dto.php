<?php


namespace Sportal\FootballApi\Application\Season\Input\Update;


use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_TournamentSeasonUpdateInput")
 */
class Dto implements IDto
{

    private string $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

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

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Length(['max' => 20])
            ]
        ]);
    }
}