<?php


namespace Sportal\FootballApi\Application\Standing\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Standing\Input;
use Sportal\FootballApi\Domain\Standing\EntityResolver;
use Sportal\FootballApi\Domain\Standing\Exception\InvalidStandingException;
use Sportal\FootballApi\Domain\Standing\StandingModelBuilder;

class Put implements IService
{
    private StandingModelBuilder $modelBuilder;
    private Input\Put\Mapper $inputMapper;
    private EntityResolver $entityResolver;

    /**
     * Put constructor.
     * @param StandingModelBuilder $modelBuilder
     * @param Input\Put\Mapper $inputMapper
     * @param EntityResolver $entityResolver
     */
    public function __construct(StandingModelBuilder $modelBuilder, Input\Put\Mapper $inputMapper, EntityResolver $entityResolver)
    {
        $this->modelBuilder = $modelBuilder;
        $this->inputMapper = $inputMapper;
        $this->entityResolver = $entityResolver;
    }


    /**
     * @param IDto $inputDto
     * @throws NoSuchEntityException|InvalidStandingException
     */
    public function process(IDto $inputDto)
    {
        /**
         * @var Input\Put\Dto $inputDto
         */
        $standing = $this->inputMapper->map($inputDto);
        if ($this->entityResolver->resolve($standing->getStandingEntity()->getEntityName(), $standing->getStandingEntity()->getEntityId()) === null) {
            throw new NoSuchEntityException($standing->getStandingEntity()->getEntityName()->getKey() . "," . $standing->getStandingEntity()->getEntityId());
        }
        $this->modelBuilder->build($standing)->withBlacklist()->upsert();
    }
}