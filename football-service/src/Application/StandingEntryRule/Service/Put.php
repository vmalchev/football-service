<?php


namespace Sportal\FootballApi\Application\StandingEntryRule\Service;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\StandingEntryRule\Input;
use Sportal\FootballApi\Domain\Standing\Exception\InvalidStandingException;
use Sportal\FootballApi\Domain\Standing\IStandingRepository;
use Sportal\FootballApi\Domain\Standing\StandingEntryRuleModelBuilder;

class Put implements IService
{
    private Input\Put\Mapper $inputMapper;

    private StandingEntryRuleModelBuilder $modelBuilder;

    private IStandingRepository $standingRepository;

    /**
     * Put constructor.
     * @param Input\Put\Mapper $inputMapper
     * @param StandingEntryRuleModelBuilder $modelBuilder
     * @param IStandingRepository $standingRepository
     */
    public function __construct(Input\Put\Mapper $inputMapper, StandingEntryRuleModelBuilder $modelBuilder, IStandingRepository $standingRepository)
    {
        $this->inputMapper = $inputMapper;
        $this->modelBuilder = $modelBuilder;
        $this->standingRepository = $standingRepository;
    }

    /**
     * @param IDto $inputDto
     * @throws NoSuchEntityException
     * @throws InvalidStandingException
     */
    public function process(IDto $inputDto)
    {
        /**
         * @var Input\Put\Dto $inputDto
         */
        $standingEntity = $this->standingRepository->findExisting($inputDto->getType(), $inputDto->getEntity(), $inputDto->getEntityId());
        if ($standingEntity === null) {
            throw new NoSuchEntityException("{$inputDto->getType()->getKey()},{$inputDto->getEntity()->getKey()},{$inputDto->getEntityId()}");
        }
        $this->modelBuilder->build($this->inputMapper->map($standingEntity, $inputDto->getEntryRules()))
            ->withBlacklist()
            ->upsert();
    }
}