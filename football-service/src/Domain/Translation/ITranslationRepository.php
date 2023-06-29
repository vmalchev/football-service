<?php

namespace Sportal\FootballApi\Domain\Translation;


interface ITranslationRepository
{
    /**
     * @param ITranslationEntity $translation
     * @return int
     */
    public function update(ITranslationEntity $translation): int;

    /**
     * @param ITranslationEntity $translation
     * @return int
     */
    public function create(ITranslationEntity $translation): int;

    /**
     * @param ITranslationKey[] $keys
     * @return ITranslationEntity[]
     */
    public function findByKeys(array $keys): array;

    /**
     * @param ITranslationKey $key
     * @return ITranslationEntity|null
     */
    public function find(ITranslationKey $key): ?ITranslationEntity;
}