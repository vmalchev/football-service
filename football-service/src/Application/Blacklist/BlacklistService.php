<?php


namespace Sportal\FootballApi\Application\Blacklist;


use Sportal\FootballApi\Application\Blacklist\Dto\BlacklistKeyDto;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistEntity;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;

class BlacklistService
{
    /**
     * @var IBlacklistRepository
     */
    private $blacklistRepository;

    public function __construct(IBlacklistRepository $blacklistRepository)
    {
        $this->blacklistRepository = $blacklistRepository;
    }

    public function search(array $blacklistDtos)
    {
        $blacklistKeys = array_map([BlacklistKeyDto::class, 'toBlacklistKey'], $blacklistDtos);
        $result = $this->blacklistRepository->findByKeys($blacklistKeys);

        return array_map([Dto\BlacklistDto::class, 'fromBlacklistEntity'], $result);
    }

    public function delete(string $blacklistId)
    {
        if (!$this->blacklistRepository->delete($blacklistId)) {
            throw new NoSuchEntityException();
        }
    }

    public function insertNew(array $blacklistKeys)
    {
        $blacklists = $this->blacklistRepository->findByKeys($blacklistKeys);


        foreach ($blacklistKeys as $key => $blacklistKey) {
            if ($this->inBlacklistArray($blacklists, $blacklistKey)) {
                unset($blacklistKeys[$key]);
            }
        }

        $this->blacklistRepository->insertAll($blacklistKeys);
    }

    /**
     * @param IBlacklistEntity[] $blacklists
     * @param IBlacklistKey $blacklistKey
     * @return bool
     */
    private function inBlacklistArray(array $blacklists, IBlacklistKey $blacklistKey)
    {
        foreach ($blacklists as $blacklist) {
            if ($blacklist->getBlacklistKey() == $blacklistKey) {
                return true;
            }
        }

        return false;
    }
}