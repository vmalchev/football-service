<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\EventPlayerTypeRepository;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;

class EventPlayerTypeImporter extends MappableImporter
{

    /**
     *
     * @var EventPlayerTypeRepository
     */
    protected $repository;

    /**
     *
     * @var JobDispatcherInterface
     */
    protected $dispatcher;

    public function __construct(EventPlayerTypeRepository $repository, MappingRepositoryInterface $mapping,
        LoggerInterface $logger, JobDispatcherInterface $dispatcher)
    {
        // TODO Auto-generated method stub
        parent::__construct($repository, $mapping, $logger);
        $this->dispatcher = $dispatcher;
    }

    public function import(array $data)
    {
        return $this->importModel($this->repository->createObject($data), $data['id'],
            function ($model, $changes) use ($data) {
                $this->dispatcher->dispatch('Import\MlContent',
                    [
                        $model,
                        $data['id']
                    ]);
                $this->handleChanges();
            });
    }
}