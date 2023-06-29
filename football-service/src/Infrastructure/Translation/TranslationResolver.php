<?php


namespace Sportal\FootballApi\Infrastructure\Translation;


use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Infrastructure\Mapper\RecursiveMapper;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Sportal\FootballApi\Infrastructure\Translation\TranslationRepository;

class TranslationResolver
{

    private RecursiveMapper $mapper;

    private TranslationRepository $translationRepository;

    public function __construct(RecursiveMapper $mapper, TranslationRepository $translationRepository)
    {
        $this->mapper = $mapper;
        $this->translationRepository = $translationRepository;
    }

    public function resolve($dto, string $languageCode)
    {
        $data = $dto;

        $objectMap = [];
        $translationKeys = $this->mapper->map($data,
            fn($data) => $data instanceof ITranslatable && !is_null($data->getId()),
            function (ITranslatable $object) use (&$objectMap, $languageCode) {
                $filter = new TranslationKey($object->getTranslationEntityType(), $object->getId(), $languageCode);
                $objectMap[(string)$filter][] = $object;
                return $filter;
            }
        );

        $translations = $this->translationRepository->findByKeys($translationKeys);

        foreach ($translations as $translation) {
            $filter = $translation->getTranslationKey();
            $objects = $objectMap[(string)$filter] ?? [];
            foreach ($objects as $object) {
                /**
                 * @var ITranslatable $object
                 */
                $object->setTranslation($translation->getContent());
            }
        }

        return $data;
    }
}