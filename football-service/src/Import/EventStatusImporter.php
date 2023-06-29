<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\EventStatusRepository;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;

class EventStatusImporter extends MappableImporter
{

    protected $dispatcher;

    public function __construct(EventStatusRepository $repository, MappingRepositoryInterface $mapping,
        LoggerInterface $logger, JobDispatcherInterface $dispatcher)
    {
        parent::__construct($repository, $mapping, $logger);
        $this->dispatcher = $dispatcher;
    }

    /**
     *
     * @param array $eventStatusArr
     * @return Sportal\FootballApi\Model\EventStatus
     */
    public function import(array $eventStatusArr)
    {
        $eventStatus = $this->repository->createObject($eventStatusArr);
        return $this->importModel($eventStatus, $eventStatusArr['id'],
            function ($model) {
                $this->dispatcher->dispatch('Import\MlContent', [
                    $model
                ]);
            });
    }
}