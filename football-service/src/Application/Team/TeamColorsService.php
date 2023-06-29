<?php


namespace Sportal\FootballApi\Application\Team;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Team\Dto\TeamColorsDto;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntityFactory;
use Sportal\FootballApi\Domain\Team\ITeamColorsRepository;
use Sportal\FootballApi\Domain\Team\ITeamColorsRule;

class TeamColorsService
{

    private ITeamColorsRepository $teamColorsRepository;
    private ITeamColorsEntityFactory $colorsEntityFactory;
    private Output\Colors\Mapper $colorsMapper;
    private ITeamColorsRule $colorsRule;

    /**
     * TeamColorsService constructor.
     * @param ITeamColorsRepository $teamColorsRepository
     * @param Output\Colors\Mapper $colorsMapper
     * @param ITeamColorsEntityFactory $colorsEntityFactory
     * @param ITeamColorsRule $colorsRule
     */
    public function __construct(ITeamColorsRepository $teamColorsRepository,
                                Output\Colors\Mapper $colorsMapper,
                                ITeamColorsEntityFactory $colorsEntityFactory,
                                ITeamColorsRule $colorsRule)
    {
        $this->teamColorsRepository = $teamColorsRepository;
        $this->colorsMapper = $colorsMapper;
        $this->colorsEntityFactory = $colorsEntityFactory;
        $this->colorsRule = $colorsRule;
    }


    /**
     * @throws NoSuchEntityException
     */
    public function process(TeamColorsDto $teamColorsDto): TeamColorsDto
    {
        $id = $teamColorsDto->getEntityId();
        $entityType = $teamColorsDto->getEntityType();

        $this->colorsRule->validate($entityType, $id);

        $colorsEntity = $this->colorsEntityFactory->setEmpty()
            ->setEntityId($id)
            ->setEntityType($entityType)
            ->setColors($teamColorsDto->getColors())
            ->create();

        $response = $this->teamColorsRepository->upsert($colorsEntity);

        return $this->colorsMapper->map($response);
    }
}