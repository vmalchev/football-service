<?php


namespace Sportal\FootballApi\Application\Group\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Group\Input\Upsert\CollectionDto;
use Sportal\FootballApi\Application\Group;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Group\IGroupModel;
use Sportal\FootballApi\Domain\Group\IGroupRule;
use Sportal\FootballApi\Domain\Stage\IStageRepository;

class Upsert implements IService
{

    private IGroupModel $groupModel;

    private IGroupRule $groupRule;

    private Group\Input\Upsert\Mapper $inputMapper;

    private Group\Output\Get\Mapper $outputMapper;

    private IStageRepository $stageRepository;

    public function __construct(IGroupRule $groupRule,
                                Group\Input\Upsert\Mapper $inputMapper,
                                IGroupModel $groupModel,
                                Group\Output\Get\Mapper $outputMapper,
                                IStageRepository $stageRepository)
    {
        $this->groupRule = $groupRule;
        $this->inputMapper = $inputMapper;
        $this->groupModel = $groupModel;
        $this->outputMapper = $outputMapper;
        $this->stageRepository = $stageRepository;
    }

    public function process(IDto $inputDto): CollectionDto
    {
        $stage = $this->stageRepository->findById($inputDto->getStageId());
        if (is_null($stage)) {
            throw new NoSuchEntityException('Stage ' . $inputDto->getStageId());
        }

        $groupEntities = $this->inputMapper->map($inputDto);

        $this->groupRule->validate($stage, $groupEntities);

        $groups = $this->groupModel
            ->setGroups($groupEntities)
            ->withBlacklist()
            ->upsert()
            ->getGroups();

        $outputDto = array_map(fn($group) => $this->outputMapper->map($group), $groups);

        return new CollectionDto($outputDto);
    }

}