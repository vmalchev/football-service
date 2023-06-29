<?php


namespace Sportal\FootballApi\Application\Player;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Player\Output\Get\Profile\Dto;
use Sportal\FootballApi\Application\Player\Output\Get\Profile\Mapper;
use Sportal\FootballApi\Domain\Player\IPlayerProfileBuilder;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;

class ProfileService
{

    private IPlayerRepository $playerRepository;
    private Mapper $playerProfileMapper;
    private IPlayerProfileBuilder $playerProfileBuilder;

    /**
     * ProfileService constructor.
     * @param IPlayerRepository $playerRepository
     * @param Mapper $playerProfileMapper
     * @param IPlayerProfileBuilder $playerProfileBuilder
     */
    public function __construct(IPlayerRepository $playerRepository,
                                Mapper $playerProfileMapper,
                                IPlayerProfileBuilder $playerProfileBuilder)
    {
        $this->playerRepository = $playerRepository;
        $this->playerProfileMapper = $playerProfileMapper;
        $this->playerProfileBuilder = $playerProfileBuilder;
    }


    /**
     * @AttachAssets
     * @param int $id
     * @return Dto
     * @throws NoSuchEntityException
     */
    public function process(int $id): Dto
    {
        $playerEntity = $this->playerRepository->findById($id);
        if (is_null($playerEntity)) {
            throw new NoSuchEntityException();
        }

        $playerProfileEntity = $this->playerProfileBuilder->build($playerEntity);

        return $this->playerProfileMapper->map($playerProfileEntity);
    }
}