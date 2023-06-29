<?php
namespace Sportal\FootballApi\Repository;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;

class MappingRepositoryContainer
{

    /**
     *
     * @var MappingRepositoryInterface[]
     */
    private $repositories;

    /**
     *
     * @var MappingRepositoryInterface
     */
    private $default;

    public function __construct(array $repositories, MappingRepositoryInterface $default)
    {
        $this->repositories = $repositories;
        $this->default = $default;
    }

    /**
     *
     * Get a mapping repository by name
     * @param string $name
     * @throws \InvalidArgumentException if the specified repository is not found
     * @return \Sportal\FootballApi\Repository\MappingRepositoryInterface
     */
    public function get($name)
    {
        if ($name === null) {
            return $this->default;
        } elseif (isset($this->repositories[$name]) && $this->repositories[$name] instanceof MappingRepositoryInterface) {
            return $this->repositories[$name];
        }
        
        throw new \InvalidArgumentException('No mapping repository for ' . $name . ' is available');
    }

    /**
     *
     * @return \Sportal\FootballApi\Repository\MappingRepositoryInterface[]
     */
    public function getAll()
    {
        return $this->repositories;
    }

    /**
     * @return \Sportal\FootballApi\Repository\MappingRepositoryInterface
     */
    public function getDefault()
    {
        return $this->default;
    }
}