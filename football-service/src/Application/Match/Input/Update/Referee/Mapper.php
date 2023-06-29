<?php


namespace Sportal\FootballApi\Application\Match\Input\Update\Referee;


use Sportal\FootballApi\Domain\Match\IMatchRefereeEntity;
use Sportal\FootballApi\Domain\Match\IMatchRefereeEntityFactory;
use Sportal\FootballApi\Domain\Match\MatchRefereeRole;

class Mapper
{
    private IMatchRefereeEntityFactory $factory;

    /**
     * Mapper constructor.
     * @param IMatchRefereeEntityFactory $factory
     */
    public function __construct(IMatchRefereeEntityFactory $factory)
    {
        $this->factory = $factory;
    }


    /**
     * @param Dto[]|null $input
     * @return IMatchRefereeEntity[]|null
     */
    public function map(?array $input): ?array
    {
        if (empty($input)) {
            return null;
        }

        return array_map(fn(Dto $dto) => $this->factory->setEmpty()
            ->setRefereeId($dto->getRefereeId())
            ->setRole(MatchRefereeRole::forKey($dto->getRole()))
            ->create(), $input);
    }
}