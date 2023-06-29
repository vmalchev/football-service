<?php


namespace Sportal\FootballApi\Application\TeamSquad\Input\Patch;


use DateTimeImmutable;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntityFactory;
use Sportal\FootballApi\Domain\TeamSquad\PlayerContractType;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class PlayerMapper
{
    private ITeamPlayerEntityFactory $entityFactory;

    /**
     * PlayerMapper constructor.
     * @param ITeamPlayerEntityFactory $entityFactory
     */
    public function __construct(ITeamPlayerEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }


    /**
     * @param Dto $input
     * @return ITeamPlayerEntity[]|null
     */
    public function map(Dto $input): ?array
    {
        if ($input->getPlayers() !== null) {
            $playerEntities = [];
            foreach ($input->getPlayers() as $dto) {
                $playerEntities[] = $this->entityFactory->setEmpty()
                    ->setTeamId($input->getTeamId())
                    ->setPlayerId($dto->getPlayerId())
                    ->setStatus(new TeamSquadStatus($dto->getStatus()))
                    ->setContractType(!is_null($dto->getContractType()) ? new PlayerContractType($dto->getContractType()) : PlayerContractType::UNKNOWN())
                    ->setStartDate(!is_null($dto->getStartDate()) ? new DateTimeImmutable($dto->getStartDate()) : null)
                    ->setEndDate(!is_null($dto->getEndDate()) ? new DateTimeImmutable($dto->getEndDate()) : null)
                    ->setShirtNumber($dto->getShirtNumber())
                    ->create();
            }
            return $playerEntities;
        }
        return null;
    }
}