<?php


namespace Sportal\FootballApi\Application\Match\Service;


use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Match;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;
use Sportal\FootballApi\Domain\Match\IMatchModelBuilder;
use Sportal\FootballApi\Domain\Match\IMatchProfileBuilder;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


class Update implements IService
{
    private Match\Input\Update\Mapper $inputMapper;

    private IMatchProfileBuilder $profileBuilder;

    private IMatchModelBuilder $modelBuilder;

    private Match\Output\Get\Mapper $outputMapper;

    private IMatchRepository $matchRepository;

    /**
     * Create constructor.
     * @param Match\Input\Update\Mapper $inputMapper
     * @param IMatchProfileBuilder $profileBuilder
     * @param IMatchModelBuilder $modelBuilder
     * @param Match\Output\Get\Mapper $outputMapper
     * @param IMatchRepository $matchRepository
     */
    public function __construct(Match\Input\Update\Mapper $inputMapper,
                                IMatchProfileBuilder $profileBuilder,
                                IMatchModelBuilder $modelBuilder,
                                Match\Output\Get\Mapper $outputMapper, IMatchRepository $matchRepository)
    {
        $this->inputMapper = $inputMapper;
        $this->profileBuilder = $profileBuilder;
        $this->modelBuilder = $modelBuilder;
        $this->outputMapper = $outputMapper;
        $this->matchRepository = $matchRepository;
    }


    /**
     * @AttachEventNotification(object=EventNotificationEntityType::MATCH,operation=EventNotificationOperationType::UPDATE)
     * @AttachAssets
     * @param IDto $inputDto
     * @return Match\Output\Get\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Match\Output\Get\Dto
    {
        /** @var $inputDto Match\Input\Update\Dto */
        if (!$this->matchRepository->existsById($inputDto->getId())) {
            throw new NoSuchEntityException();
        }
        $matchEntity = $this->modelBuilder->build($this->inputMapper->map($inputDto))
            ->withBlacklist()
            ->update()
            ->getMatch();
        return $this->outputMapper->map($this->profileBuilder->build($matchEntity));
    }
}