<?php


namespace Sportal\FootballApi\Application\Group\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Group\Output\Upsert\CollectionDto;
use Sportal\FootballApi\Application\Group\Output\Get\Mapper;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Group\IGroupRepository;
use Sportal\FootballApi\Domain\Stage\IStageRepository;

class ListAll implements IService
{

    private IGroupRepository $groupRepository;

    private IStageRepository $stageRepository;

    private Mapper $groupMapper;

    public function __construct(IGroupRepository $groupRepository,
                                IStageRepository $stageRepository,
                                Mapper $groupMapper)
    {
        $this->groupRepository = $groupRepository;
        $this->stageRepository = $stageRepository;
        $this->groupMapper = $groupMapper;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): CollectionDto
    {
        $stageId = $inputDto->getStageId();

        if (is_null($this->stageRepository->findById($stageId))) {
            throw new NoSuchEntityException('Stage ' . $stageId);
        }

        $entities = $this->groupRepository->findByStageId($stageId);

        $dto = array_map(fn($entity) => $this->groupMapper->map($entity), $entities);
        return new CollectionDto($dto);
    }
}