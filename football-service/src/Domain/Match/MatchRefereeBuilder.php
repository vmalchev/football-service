<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\Match\Exception\InvalidMatchException;
use Sportal\FootballApi\Domain\Referee\IRefereeRepository;

class MatchRefereeBuilder
{
    private IRefereeRepository $refereeRepository;

    private IMatchRefereeEntityFactory $refereeEntityFactory;

    /**
     * MatchRefereeBuilder constructor.
     * @param IRefereeRepository $refereeRepository
     * @param IMatchRefereeEntityFactory $refereeEntityFactory
     */
    public function __construct(IRefereeRepository $refereeRepository, IMatchRefereeEntityFactory $refereeEntityFactory)
    {
        $this->refereeRepository = $refereeRepository;
        $this->refereeEntityFactory = $refereeEntityFactory;
    }

    /**
     * @param IMatchRefereeEntity[] $inputReferees
     * @return IMatchRefereeEntity[]
     * @throws InvalidMatchException
     */
    public function build(?array $inputReferees): ?array
    {
        if (empty($inputReferees)) {
            return null;
        }

        return array_map(function (IMatchRefereeEntity $inputReferee) {
            $refereeEntity = $this->refereeRepository->findById($inputReferee->getRefereeId());
            if ($refereeEntity === null) {
                throw new InvalidMatchException("Invalid refereeId:{$inputReferee->getRefereeId()}");
            }
            return $this->refereeEntityFactory->setFrom($inputReferee)
                ->setRole($inputReferee->getRole())
                ->setRefereeId($refereeEntity->getId())
                ->setRefereeName($refereeEntity->getName())
                ->create();
        }, $inputReferees);

    }

}