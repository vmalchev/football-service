<?php
namespace Sportal\FootballApi\Domain\LiveCommentary;


class LiveCommentaryCollection implements ILiveCommentaryCollection
{
    /**
     * @var ILiveCommentaryRepository
     */
    private $repository;

    /**
     * @var LiveCommentaryBuilder
     */
    private $builder;

    /**
     * @var ILiveCommentaryModel[]
     */
    private $liveCommentaries;

    /**
     * @var LiveCommentarySpecification
     */
    private $specification;

    public function __construct(
        ILiveCommentaryRepository $repository,
        LiveCommentaryBuilder $builder,
        LiveCommentarySpecification $specification
    ) {
        $this->repository = $repository;
        $this->builder = $builder;
        $this->specification = $specification;
    }

    public function get(): array
    {
        return $this->liveCommentaries;
    }

    /**
     * @param ILiveCommentaryEntity[] $entities
     * @return ILiveCommentaryModel[]
     */
    private function buildCollection(array $entities)
    {
        $liveCommentaries = [];

        foreach ($entities as $entity) {
            $liveCommentaries[] = $this->builder->build($entity);
        }

        $this->liveCommentaries = $liveCommentaries;

        return $this;
    }

    /**
     * @param int $matchId
     * @param string $languageCode
     * @return ILiveCommentaryModel[]
     */
    public function getByMatch(int $matchId, string $languageCode): array
    {
        $this->specification->process($matchId, $languageCode);

        $entities = $this->repository->findByMatchIdAndLanguageCode($matchId, $languageCode);

        $this->buildCollection($entities);

        return $this->liveCommentaries;
    }

}