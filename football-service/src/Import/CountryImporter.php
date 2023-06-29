<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballApi\Repository\CountryRepository;
use Sportal\FootballApi\Model\Country;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Util\NameUtil;

class CountryImporter
{

    /**
     *
     * @var CountryRepository
     */
    private $repository;

    /**
     *
     * @var MappingRepositoryInterface
     */
    private $mapping;

    /**
     *
     * @var JobDispatcherInterface
     */
    private $dispatcher;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(CountryRepository $repository, MappingRepositoryInterface $mapping,
        LoggerInterface $logger, JobDispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->mapping = $mapping;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     *
     * @param array $country
     * @return \Sportal\FootballApi\Model\Country
     */
    public function importCountry(array $countryArr)
    {
        $countryId = isset($countryArr['id']) ? $this->mapping->getOwnId(Country::class, $countryArr['id']) : null;
        
        if ($countryId === null) {
            if (($country = $this->repository->findByName($countryArr['name'])) === null) {
                $country = $this->repository->createObject($countryArr);
                $this->repository->create($country);
                $this->logger->info(
                    NameUtil::shortClassName(get_class($this)) . " Created " .
                         NameUtil::shortClassName(get_class($country)) . " " . $country->getId() . "-" .
                         $countryArr['name']);
                if (isset($countryArr['id']) && $this->dispatcher->supports('Import\MlContent')) {
                    $this->dispatcher->dispatch('Import\MlContent',
                        [
                            $country,
                            $countryArr['id']
                        ]);
                }
                $this->repository->refreshCache([], [
                    $country
                ]);
            }
            if (isset($countryArr['id'])) {
                $this->mapping->setMapping(Country::class, $countryArr['id'], $country->getId());
                $this->logger->info(
                    NameUtil::shortClassName(get_class($this)) . " Mapped " .
                         NameUtil::shortClassName(get_class($country)) . " " . $country->getId() . "->" .
                         $countryArr['id']);
            }
        } else {
            $country = $this->repository->find($countryId);
        }
        
        return $country;
    }
}