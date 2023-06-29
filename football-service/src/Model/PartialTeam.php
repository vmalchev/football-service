<?php

namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Asset\ContainsAssets;
use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryDetailData;
use Sportal\FootballApi\Domain\Team\TeamType;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *      definition="PartialTeamWithForm",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/PartialTeam"),
 *          @SWG\Schema(type="object", properties={
 *              @SWG\Property(property="form",
 *                            description="Form guide for the Team if available and requested as an option",
 *                            type="array",
 *                            @SWG\Items(ref="#/definitions/TeamForm"))
 *          })
 *      })
 *
 *
 * @SWG\Definition(required={"id", "name"})
 */
class PartialTeam implements Assetable, MlContainerInterface, \JsonSerializable, Translateable, ILiveCommentaryDetailData
{
    use ContainsAssets, ContainsMlContent;

    const MODEL_NAME = 'team';

    /**
     * Whether the team is a football club(Barcelona) or national team(Spain)
     * @var boolean
     * @SWG\Property()
     */
    private $national;

    /**
     * Unique identifier of the Team within the system
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Human readable name of the team
     * @var string
     * @SWG\Property(example="Real Madrid")
     */
    private $name;

    /**
     * 3 character name of team
     * @var string|null
     * @SWG\Property(example="RMA")
     */
    private $threeLetterCode;

    /**
     * Manually inserted short name of team
     * @var string|null
     * @SWG\Property(example="Loko Plovdiv")
     */
    private $shortName;

    /**
     * If present and = true, the team can be one of two teams. This occurs in cup competitions when some games are still not played
     * @var boolean
     * @SWG\Property()
     */
    private $undecided;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=TEAM_GENDER, description="Team gender, MALE, FEMALE or null")
     */
    private $gender;

    /**
     * @SWG\Property(property="url_logo", type="string", example="http://football-api.devks.msk.bg/assets/image.png")
     */


    /**
     * @SWG\Property(property="type", type="string", enum={"placeholder", "club", "national"})
     */

    /**
     *
     * @var \Sportal\FootballApi\Model\TeamForm[]
     */
    private $form;

    private static $assetTypes = [
        'logo'
    ];

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set national
     *
     * @param boolean $national
     *
     * @return Team
     */
    public function setNational($national)
    {
        $this->national = (bool)$national;

        return $this;
    }

    /**
     * Get national
     *
     * @return boolean
     */
    public function getNational()
    {
        return $this->national;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\SurrogateKeyInterface::setId()
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     * @return PartialTeam
     */
    public function setGender(?string $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\TeamForm[]
     */
    public function getFormGuide()
    {
        return $this->form;
    }

    public function setFormGuide(array $form = null)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Asset\Assetable::getAssetTypes()
     */
    public function getAssetTypes()
    {
        return static::$assetTypes;
    }

    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'three_letter_code' => $this->threeLetterCode,
            'type' => $this->getType(),
            'gender' => $this->gender,
            'short_name' => $this->shortName,
        ];

        if ($this->undecided != null) {
            $data['undecided'] = $this->undecided;
        }

        $data = array_merge($data, $this->getAssetUrls());

        if (!empty($this->form)) {
            $data['form'] = $this->form;
        }

        return $this->translateContent($data);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $arr = [
            $this
        ];

        if (!empty($this->form)) {
            foreach ($this->form as $form) {
                $arr = array_merge($arr, $form->getMlContentModels());
            }
        }
        return $arr;
    }

    public function getContainerName()
    {
        return static::MODEL_NAME;
    }

    public function getAssetModelName()
    {
        return static::MODEL_NAME;
    }

    /**
     *
     * @return boolean
     */
    public function getUndecided()
    {
        return $this->undecided;
    }

    /**
     * Get team type
     *
     * @return string
     */
    public function getType()
    {
        $undecided = $this->undecided ?? null;
        $national = $this->national ?? null;
        if ($undecided === true) {
            return TeamType::PLACEHOLDER();
        } elseif ($national === true) {
            return TeamType::NATIONAL();
        }

        return TeamType::CLUB();
    }

    /**
     *
     * @param boolean $undecided
     * @return \Sportal\FootballApi\Model\PartialTeam
     */
    public function setUndecided($undecided)
    {
        $this->undecided = (boolean)$undecided;
        return $this;
    }

    public function getPlaceholderValue()
    {
        return $this->mlContent[$this->langCode]['name'] ?? $this->name;
    }

    /**
     * @return string|null
     */
    public function getThreeLetterCode(): ?string
    {
        return $this->threeLetterCode;
    }

    /**
     * @param string|null $threeLetterCode
     * @return PartialTeam
     */
    public function setThreeLetterCode(?string $threeLetterCode): PartialTeam
    {
        $this->threeLetterCode = $threeLetterCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * @param string|null $shortName
     * @return PartialTeam
     */
    public function setShortName(?string $shortName): PartialTeam
    {
        $this->shortName = $shortName;
        return $this;
    }

}

