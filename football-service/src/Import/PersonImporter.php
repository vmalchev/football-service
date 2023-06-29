<?php
namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Asset\AssetManager;
use Sportal\FootballApi\Repository\PersonRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;

abstract class PersonImporter extends Importer
{

    /**
     *
     * @var PersonRepository
     */
    protected $repository;

    protected $countryImporter;

    protected $dispatcher;

    protected $assetManager;

    public function __construct(PersonRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, CountryImporter $countryImporter, JobDispatcherInterface $dispatcher,
        AssetManager $assetManager)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->countryImporter = $countryImporter;
        $this->dispatcher = $dispatcher;
        $this->assetManager = $assetManager;
    }

    /**
     *
     * @param array $personData
     * @param mixed $feedId
     * @param string $sourceName
     * @return \Sportal\FootballApi\Model\Person
     */
    protected function importData(array $personData, $feedId, $sourceName = null, $allowCreate = true)
    {
        $personData['country'] = $this->countryImporter->importCountry($personData['country']);
        $model = $this->repository->createObject($personData);
        $person = $this->importMerge($model, $feedId, $sourceName, $allowCreate,
            function ($model, $changes) use ($feedId) {
                $this->dispatcher->dispatch('Import\MlContent',
                    [
                        $model,
                        $feedId
                    ]);
            });
        $this->handleChanges();
        return $person;
    }

    /**
     *
     * @param mixed $feedId
     * @param boolean $partial
     * @param string $sourceName
     * @return \Sportal\FootballApi\Model\Person
     */
    public function getOrImport($feedId, $partial = false, $sourceName = null)
    {
        $mapping = ($sourceName !== null) ? $this->mappings->get($sourceName) : $this->mappings->getDefault();
        $ownPlayerId = $mapping->getOwnId($this->repository->getModelClass(), $feedId);
        if ($ownPlayerId === null || ($player = $this->repository->find($ownPlayerId)) === null) {
            $player = $this->import($feedId, $sourceName);
        }
        return ($partial && $player !== null) ? $this->repository->clonePartial($player) : $player;
    }

    /**
     *
     * @param mixed $feedId
     * @param string $sourceName
     * @return \Sportal\FootballApi\Model\Person
     */
    abstract public function import($feedId, $sourceName = null);
}