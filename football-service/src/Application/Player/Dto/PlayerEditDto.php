<?php


namespace Sportal\FootballApi\Application\Player\Dto;

use App\Validation\Identifier;
use JsonSerializable;
use Sportal\FootballApi\Application\Team\Dto\PlayerSocialDto;
use Sportal\FootballApi\Domain\Player\IPlayerEditEntity;
use Sportal\FootballApi\Domain\Player\PlayerPosition;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class PlayerEditDto implements JsonSerializable
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
    private $country_id;

    /**
     * @var bool|null
     * @SWG\Property()
     */
    private $active;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $birthdate;

    /**
     * @var string
     * @SWG\Property()
     */
    private $birth_city_id;

    /**
     * @var PlayerProfileDto|null
     * @SWG\Property()
     */
    private $profile;

    /**
     * @var PlayerEditSocialDto|null
     * @SWG\Property()
     */
    private $social;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $position;

    /**
     * PlayerEditDto constructor.
     * @param string|null $name
     * @param string|null $country_id
     * @param string|null $birthdate
     * @param string|null $birth_city_id
     * @param string|null $active
     * @param PlayerProfileDto|null $profile
     * @param PlayerEditSocialDto|null $social
     * @param string|null $position
     */
    public function __construct(
        $name = null,
        $country_id = null,
        $active = null,
        $birthdate = null,
        $birth_city_id = null,
        ?PlayerProfileDto $profile = null,
        ?PlayerEditSocialDto $social = null,
        $position = null
    )
    {
        $this->name = $name;
        $this->country_id = $country_id;
        $this->active = $active;
        $this->birthdate = $birthdate;
        $this->birth_city_id = $birth_city_id;
        $this->profile = $profile;
        $this->social = $social;
        $this->position = $position;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new NotBlank(),
            new Length(['max' => 50]),
        ]);

        $metadata->addPropertyConstraints('country_id', [
            new NotBlank(),
            new Type(['type' => ['digit', 'numeric']]),
            new Identifier(),
        ]);

        $metadata->addPropertyConstraints('birth_city_id', [
            new NotBlank(['allowNull' => true]),
            new Type(['type' => ['digit', 'numeric']]),
            new Identifier(),
        ]);

        $metadata->addPropertyConstraint('birthdate', new Date());

        $metadata->addPropertyConstraint('active', new Type('bool'));

        $metadata->addPropertyConstraint('profile', new Valid());

        $metadata->addPropertyConstraint('position', new Choice([
            'choices' => array_values(PlayerPosition::keys()),
            'message' => 'Choose a valid position. Options are: ' . implode(", ", PlayerPosition::keys()),
        ]));
    }

    public static function fromPlayerEntity(IPlayerEditEntity $playerEntity)
    {
        if (!is_null($playerEntity->getSocial())) {
            $social = new PlayerEditSocialDto(
                $playerEntity->getSocial()->getWeb(),
                $playerEntity->getSocial()->getTwitterId(),
                $playerEntity->getSocial()->getFacebookId(),
                $playerEntity->getSocial()->getInstagramId(),
                $playerEntity->getSocial()->getWikipediaId(),
                $playerEntity->getSocial()->getYoutubeChannelId()
            );
        } else {
            $social = null;
        }

        return new PlayerEditDto(
            $playerEntity->getName(),
            $playerEntity->getCountryId(),
            $playerEntity->getActive(),
            $playerEntity->getBirthdate()->format("Y-d-m"),
            new PlayerProfileDto(
                $playerEntity->getProfile()->getHeight(),
                $playerEntity->getProfile()->getWeight()
            ),
            $social,
            $playerEntity->getPosition()
        );
    }

    /**
     * @return PlayerEditSocialDto|null
     */
    public function getSocial(): ?PlayerEditSocialDto
    {
        return $this->social;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCountryId(): int
    {
        return $this->country_id;
    }

    /**
     * @return \DateTime|null
     * @throws \Exception
     */
    public function getBirthdate(): ?\DateTimeInterface
    {
        return !is_null($this->birthdate) ? new \DateTime($this->birthdate) : null;
    }

    /**
     * @return int|null
     */
    public function getBirthCityId(): ?int
    {
        return $this->birth_city_id;
    }

    /**
     * @return PlayerProfileDto|null
     */
    public function getProfile(): ?PlayerProfileDto
    {
        return $this->profile;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}