<?php

namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\Round\IRoundEntityFactory;
use Sportal\FootballApi\Domain\Round\IRoundRepository;
use Sportal\FootballApi\Domain\Round\RoundType;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballFeedCommon\RoundType\RoundTypeFeedInterface;

class RoundTypeImporter
{

    private RoundTypeFeedInterface $roundTypeFeed;

    private MappingRepositoryInterface $mappingRepository;

    private IRoundEntityFactory $entityFactory;

    private IRoundRepository $roundRepository;

    private ITransactionManager $transactionManager;

    public function __construct(MappingRepositoryInterface $mappingRepository,
                                RoundTypeFeedInterface $roundTypeFeed,
                                IRoundEntityFactory $entityFactory,
                                IRoundRepository $roundRepository,
                                ITransactionManager $transactionManager)
    {
        $this->mappingRepository = $mappingRepository;
        $this->roundTypeFeed = $roundTypeFeed;
        $this->entityFactory = $entityFactory;
        $this->roundRepository = $roundRepository;
        $this->transactionManager = $transactionManager;
    }

    public function import(string $roundTypeId)
    {
        $roundType = $this->roundTypeFeed->getRoundById($roundTypeId);

        if (is_null($roundType)
            || !is_null($this->mappingRepository->getOwnId(RoundType::class, $roundType['id']))) {
            return;
        }

        $entity = $this->entityFactory
            ->setId($roundType['id'])
            ->setKey($roundType['name'])
            ->setName($roundType['name'])
            ->setType($roundType['knockout'] === 'yes' ? RoundType::KNOCK_OUT() : RoundType::LEAGUE())
            ->create();

        $this->transactionManager->transactional(function() use ($entity, $roundType) {
            $inserted = $this->roundRepository->insert($entity);

            $this->mappingRepository->setMapping(RoundType::class, $roundType['id'], $inserted->getId());
        });
    }

}