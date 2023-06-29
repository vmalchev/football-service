<?php


namespace Sportal\FootballApi\Domain\Lineup;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeRepository;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;

class LineupPlayerBuilder
{
    private IPlayerRepository $playerRepository;

    private ILineupPlayerEntityFactory $lineupPlayerFactory;

    private ILineupPlayerTypeRepository $lineupPlayerTypeRepository;

    /**
     * LineupPlayerBuilder constructor.
     * @param IPlayerRepository $playerRepository
     * @param ILineupPlayerEntityFactory $lineupPlayerFactory
     * @param ILineupPlayerTypeRepository $lineupPlayerTypeRepository
     */
    public function __construct(IPlayerRepository $playerRepository,
                                ILineupPlayerEntityFactory $lineupPlayerFactory,
                                ILineupPlayerTypeRepository $lineupPlayerTypeRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->lineupPlayerFactory = $lineupPlayerFactory;
        $this->lineupPlayerTypeRepository = $lineupPlayerTypeRepository;
    }

    /**
     * @param ILineupPlayerEntity[] $lineupPlayers
     * @return ILineupPlayerEntity[]
     * @throws InvalidLineupException
     */
    public function build(array $lineupPlayers): array
    {
        $playerIds = array_map(fn(ILineupPlayerEntity $entity) => $entity->getPlayerId(), $lineupPlayers);
        $players = $this->playerRepository->findByIds($playerIds);
        $types = $this->lineupPlayerTypeRepository->findAll();
        $lineupPlayerEntities = [];
        foreach ($lineupPlayers as $lineupPlayer) {
            $player = $players->getById($lineupPlayer->getPlayerId());
            $type = $types->getById($lineupPlayer->getTypeId());
            if (is_null($player)) {
                throw new InvalidLineupException("Player id: {$lineupPlayer->getPlayerId()} does not exist");
            }
            if (is_null($type)) {
                throw new InvalidLineupException("Player type id: {$lineupPlayer->getTypeId()} does not exist");
            }

            $lineupPlayerEntities[] = $this->lineupPlayerFactory
                ->setEntity($lineupPlayer)
                ->setPlayerName($player->getName())
                ->setPlayerId($player->getId())
                ->setPlayer($player)
                ->setTypeId($type->getId())
                ->setType($type)
                ->create();
        }

        return $lineupPlayerEntities;
    }
}