<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Domain\Translation\ITranslationEntity;
use Sportal\FootballApi\Domain\Translation\ITranslationRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Model\MlContainerInterface;
use Sportal\FootballApi\Model\Translateable;

class MlContentRepository
{

    private ITranslationRepository $translationRepository;

    /**
     * MlContentRepository constructor.
     * @param ITranslationRepository $translationRepository
     */
    public function __construct(ITranslationRepository $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }


    /**
     * Create MlContent model based on an array of information.
     * @param array $mlContent
     * @return ITranslationEntity
     */
    public function createObject(array $mlContent): ITranslationEntity
    {
        return Translation::create($mlContent);
    }

    /**
     * @param $primaryKey
     * @return ITranslationEntity|null
     */
    public function find($primaryKey): ?ITranslationEntity
    {
        $key = new TranslationKey(new TranslationEntity($primaryKey[Translation::FIELD_ENTITY]),
            $primaryKey[Translation::FIELD_ENTITY_ID], $primaryKey[Translation::FIELD_LANGUAGE_CODE]);
        return $this->translationRepository->find($key);
    }

    /**
     *
     * @param Translateable[] $objects
     * @param string $langCode
     */
    public function translate(array $objects, $langCode)
    {
        $indexedModels = [];
        $translationKeys = [];
        foreach ($objects as $object) {
            foreach ($object->getMlContentModels() as $model) {
                if (TranslationEntity::isValid($model->getContainerName())) {
                    $key = new TranslationKey(new TranslationEntity($model->getContainerName()), $model->getId(), $langCode);
                    $indexedModels[(string)$key][] = $model;
                    $translationKeys[(string)$key] = $key;
                }
            }
        }
        if (!empty($translationKeys)) {
            $translations = $this->translationRepository->findByKeys(array_values($translationKeys));
            foreach ($translations as $translation) {
                $models = $indexedModels[(string)$translation->getTranslationKey()];
                foreach ($models as $model) {
                    $model->addContent($langCode, $translation->getContent()->toArray());
                    $model->setLanguage($langCode);
                }
            }
        }
    }

    /**
     * Load multi language content into the specified language for an object.
     * @param MlContainerInterface $object
     * @param string $langCode
     */
    public function setContent(MlContainerInterface $object, $langCode)
    {
        if ($langCode !== null && !$object->hasContent($langCode)) {
            $mlContent = $this->find(
                [
                    'entity' => $object->getContainerName(),
                    'entity_id' => $object->getId(),
                    'language_code' => $langCode
                ]);
            if ($mlContent !== null) {
                $object->addContent($langCode, $mlContent->getContent()->toArray());
            }
        }
        $object->setLanguage($langCode);
    }

    public function create(ITranslationEntity $entity)
    {
        return $this->translationRepository->create($entity);
    }

    public function update(ITranslationEntity $entity)
    {
        return $this->translationRepository->update($entity);
    }
}
