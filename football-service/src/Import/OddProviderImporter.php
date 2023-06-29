<?php
namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\OddProviderRepository;

class OddProviderImporter extends Importer
{

    /**
     *
     * @var OddProviderRepository
     */
    protected $repository;

    protected $countryImporter;

    public function __construct(OddProviderRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, CountryImporter $countryImporter)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->countryImporter = $countryImporter;
    }

    public function importData(array $oddProviderArr)
    {
        $oddProviderArr['country'] = $this->countryImporter->importCountry($oddProviderArr['country']);
        $oddProvider = $this->repository->createObject($oddProviderArr);
        return $this->importModel($oddProvider, $oddProviderArr['id']);
    }
}
