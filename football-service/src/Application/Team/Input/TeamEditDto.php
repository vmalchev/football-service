<?php


namespace Sportal\FootballApi\Application\Team\Input;

use App\Validation\Identifier;
use Sportal\FootballApi\Domain\Team\TeamGender;
use Sportal\FootballApi\Application\Team\Dto\TeamSocialDto;
use Sportal\FootballApi\Domain\Team\TeamType;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition(required={"name", "country_id","type"})
 */
class TeamEditDto
{

    /**
     * @var string
     * @SWG\Property()
     */
    private $name;


    /**
     * @var string
     * @SWG\Property()
     */
    private $three_letter_code;

    /**
     * @var string
     * @SWG\Property()
     */
    private $short_name;

    /**
     * @var string
     * @SWG\Property()
     */
    private $type;

    /**
     * @var string
     * @SWG\Property()
     */
    private $country_id;

    /**
     * @var string
     * @SWG\Property()
     */
    private $venue_id;

    /**
     * @var string
     * @SWG\Property()
     */
    private $president_id;

    /**
     * @var string
     * @SWG\Property()
     */
    private $coach_id;

    /**
     * @var TeamSocialDto|null
     * @SWG\Property()
     */
    private $social;

    /**
     * @var int
     * @SWG\Property()
     */
    private $founded;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $gender;

    /**
     * TeamEditDto constructor.
     * @param string|null $name
     * @param string|null $three_letter_code
     * @param string|null
     * @param string|null $type
     * @param string|null $country_id
     * @param string|null $venue_id
     * @param string|null $president_id
     * @param string|null $coach_id
     * @param TeamSocialDto|null $social
     * @param int|null $founded
     * @param string|null $gender
     */
    public function __construct(
        ?string $name = null,
        ?string $three_letter_code = null,
        ?string $short_name = null,
        ?string $type = null,
        ?string $country_id = null,
        ?string $venue_id = null,
        ?string $president_id = null,
        ?string $coach_id = null,
        ?TeamSocialDto $social = null,
        $founded = null,
        ?string $gender = null
    )
    {
        $this->name = $name;
        $this->three_letter_code = $three_letter_code;
        $this->short_name = $short_name;
        $this->type = $type;
        $this->country_id = $country_id;
        $this->venue_id = $venue_id;
        $this->president_id = $president_id;
        $this->coach_id = $coach_id;
        $this->social = $social;
        $this->founded = $founded;
        $this->gender = $gender;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('type', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'type', new Assert\Choice(
                [
                    'choices' => array_values(TeamType::keys()),
                    'message' => 'Choose a valid type. Options are: ' . implode(", ", TeamType::keys()),
                ]
            )
        );

        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'name',
            new Assert\Length(["min" => 3, 'max' => 80])
        );

        $metadata->addPropertyConstraint(
            'three_letter_code',
            new Assert\Length(3)
        );

        $metadata->addPropertyConstraint('short_name', new Assert\NotBlank(['allowNull' => true]));
        $metadata->addPropertyConstraint(
            'short_name',
            new Assert\Length(["min" => 3, 'max' => 20])
        );

        $metadata->addPropertyConstraints(
            'country_id', [
                new NotBlank(),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]
        );

        $metadata->addPropertyConstraints(
            'venue_id', [
                new NotBlank(['allowNull' => true]),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]
        );

        $metadata->addPropertyConstraints(
            'president_id', [
                new NotBlank(['allowNull' => true]),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]
        );
        $metadata->addPropertyConstraints(
            'coach_id', [
                new NotBlank(['allowNull' => true]),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]
        );

        $metadata->addPropertyConstraints(
            'founded', [
                new NotBlank(['allowNull' => true]),
                new Type(['type' => ['digit', 'numeric']]),
            ]
        );
        
        $metadata->addPropertyConstraints(
            'gender', [
                new NotBlank(['allowNull' => true]),
                new Assert\Choice([
                    'callback' => [TeamGender::class, 'toArray'],
                    'message' => 'Choose a valid type. Options are: ' . implode(", ", TeamGender::values())
                ])
            ]
        );

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getThreeLetterCode(): ?string
    {
        return $this->three_letter_code;
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
    public function getCountryId(): string
    {
        return $this->country_id;
    }

    /**
     * @return string|null
     */
    public function getVenueId(): ?string
    {
        return $this->venue_id;
    }

    /**
     * @return string|null
     */
    public function getPresidentId(): ?string
    {
        return $this->president_id;
    }

    /**
     * @return TeamSocialDto|null
     */
    public function getSocial(): ?TeamSocialDto
    {
        return $this->social;
    }

    /**
     * @return int|null
     */
    public function getFounded(): ?int
    {
        return $this->founded;
    }

    /**
     * @return string
     */
    public function getCoachId(): ?string
    {
        return $this->coach_id;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->short_name;
    }
}