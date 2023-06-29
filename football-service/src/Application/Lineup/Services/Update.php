<?php


namespace Sportal\FootballApi\Application\Lineup\Services;


use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Lineup\Input\Edit\Dto;
use Sportal\FootballApi\Application\Lineup\Input\Edit\Mapper;
use Sportal\FootballApi\Application\Lineup\Output;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;
use Sportal\FootballApi\Domain\Lineup\ILineupModelBuilder;
use Sportal\FootballApi\Domain\Lineup\InvalidLineupException;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


class Update implements IService
{
    private Mapper $inputMapper;
    private Output\Profile\Mapper $outputMapper;
    private ILineupModelBuilder $lineupModelBuilder;
    private IMatchRepository $matchRepository;

    /**
     * Update constructor.
     * @param Mapper $inputMapper
     * @param Output\Profile\Mapper $outputMapper
     * @param ILineupModelBuilder $lineupModelBuilder
     * @param IMatchRepository $matchRepository
     */
    public function __construct(Mapper $inputMapper, Output\Profile\Mapper $outputMapper, ILineupModelBuilder $lineupModelBuilder, IMatchRepository $matchRepository)
    {
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
        $this->lineupModelBuilder = $lineupModelBuilder;
        $this->matchRepository = $matchRepository;
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::MATCH_LINEUP,operation=EventNotificationOperationType::UPDATE)
     * @param IDto $inputDto
     * @return Output\Profile\Dto
     * @throws NoSuchEntityException|InvalidLineupException
     */
    public function process(IDto $inputDto): Output\Profile\Dto
    {
        /** @var Dto $inputDto */
        if (!$this->matchRepository->existsById($inputDto->getMatchId())) {
            throw new NoSuchEntityException($inputDto->getMatchId());
        }

        $model = $this->lineupModelBuilder->build($this->inputMapper->map($inputDto))->withBlacklist();
        $model->upsert();

        return $this->outputMapper->map($model->getLineupProfile());
    }
}
