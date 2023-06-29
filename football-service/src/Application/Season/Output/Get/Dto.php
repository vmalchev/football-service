<?php


namespace Sportal\FootballApi\Application\Season\Output\Get;

use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Application\Tournament;
use Sportal\FootballApi\Domain\Season\SeasonStatus;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Season")
 */
class Dto implements IDto, JsonSerializable, ITranslatable
{
    /**
     * @SWG\Property(property="id")
     * @var string
     */
    protected string $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    protected string $name;

    /**
     * @SWG\Property(property="tournament")
     * @var Tournament\Output\Get\Dto|null
     */
    protected ?Tournament\Output\Get\Dto $tournament;

    /**
     * @SWG\Property(enum=SEASON_STATUS, property="status")
     * @var SeasonStatus
     */
    protected SeasonStatus $status;

    /**
     * @param string $id
     * @param string $name
     * @param Tournament\Output\Get\Dto|null $tournament
     * @param SeasonStatus $status
     */
    public function __construct(
        string $id,
        string $name,
        ?Tournament\Output\Get\Dto $tournament,
        SeasonStatus $status
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->tournament = $tournament;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Tournament\Output\Get\Dto|null
     */
    public function getTournament(): ?Tournament\Output\Get\Dto
    {
        return $this->tournament;
    }

    /**
     * @return SeasonStatus
     */
    public function getStatus(): SeasonStatus
    {
        return $this->status;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::SEASON();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}