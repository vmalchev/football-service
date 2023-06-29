<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\KnockoutScheme\Output\Mapper;
use Sportal\FootballApi\Application\KnockoutScheme\Output\Scheme\SchemeDto;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeRepository;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;

class Get implements IService
{

    private IStageRepository $stageRepository;

    private IKnockoutSchemeRepository $knockoutSchemeRepository;

    private Mapper $mapper;

    /**
     * Get constructor.
     * @param IStageRepository $stageRepository
     * @param IKnockoutSchemeRepository $knockoutSchemeRepository
     * @param Mapper $mapper
     */
    public function __construct(IStageRepository $stageRepository, IKnockoutSchemeRepository $knockoutSchemeRepository, Mapper $mapper)
    {
        $this->stageRepository = $stageRepository;
        $this->knockoutSchemeRepository = $knockoutSchemeRepository;
        $this->mapper = $mapper;
    }


    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return SchemeDto[]
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): array
    {
        $stageEntity = $this->stageRepository->findById($inputDto->getStageId());
        if (is_null($stageEntity)) {
            throw new NoSuchEntityException($inputDto->getStageId() . ' stage_id');
        }

        return $this->mapper->map($this->knockoutSchemeRepository->findByStage($stageEntity));
    }
}