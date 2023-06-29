<?php


namespace Sportal\FootballApi\Application\Match\Service;


use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Match;
use Sportal\FootballApi\Domain\Match\IMatchModelBuilder;
use Sportal\FootballApi\Domain\Match\IMatchProfileBuilder;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;


class Create implements IService
{
    private Match\Input\Update\Mapper $inputMapper;

    private IMatchProfileBuilder $profileBuilder;

    private IMatchModelBuilder $modelBuilder;

    private Match\Output\Get\Mapper $outputMapper;

    /**
     * Create constructor.
     * @param Match\Input\Update\Mapper $inputMapper
     * @param IMatchProfileBuilder $profileBuilder
     * @param IMatchModelBuilder $modelBuilder
     * @param Match\Output\Get\Mapper $outputMapper
     */
    public function __construct(Match\Input\Update\Mapper $inputMapper,
                                IMatchProfileBuilder $profileBuilder,
                                IMatchModelBuilder $modelBuilder,
                                Match\Output\Get\Mapper $outputMapper)
    {
        $this->inputMapper = $inputMapper;
        $this->profileBuilder = $profileBuilder;
        $this->modelBuilder = $modelBuilder;
        $this->outputMapper = $outputMapper;
    }


    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::MATCH,operation=EventNotificationOperationType::CREATE)
     * @param IDto $inputDto
     * @return Match\Output\Get\Dto
     */
    public function process(IDto $inputDto): Match\Output\Get\Dto
    {
        /** @var $inputDto Match\Input\Update\Dto */
        $matchEntity = $this->modelBuilder->build($this->inputMapper->map($inputDto))
            ->withBlacklist()
            ->create()
            ->getMatch();
        return $this->outputMapper->map($this->profileBuilder->build($matchEntity));
    }
}