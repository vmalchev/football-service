<?php

namespace Sportal\FootballApi\Application\Translation;

use Sportal\FootballApi\Application\Blacklist\BlacklistService;
use Sportal\FootballApi\Application\Translation\Dto\TranslationDto;
use Sportal\FootballApi\Application\Translation\Dto\TranslationEntityDto;
use Sportal\FootballApi\Application\Translation\Dto\TranslationKeyDto;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\Translation\ITranslationEntity;
use Sportal\FootballApi\Domain\Translation\ITranslationKey;
use Sportal\FootballApi\Domain\Translation\ITranslationRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Domain\Translation\TranslationMapper;
use Sportal\FootballApi\Domain\Translation\TranslationValidator;
use Sportal\FootballApi\Infrastructure\Entity\EntityExistsRepository;
use Sportal\FootballApi\Infrastructure\Translation\Translation;

class TranslationService
{
    /**
     * @var ITranslationRepository
     */
    private $translationRepository;

    /**
     * @var BlacklistService
     */
    private $blacklistService;

    /**
     * @var EntityExistsRepository
     */
    private $entityExistsRepository;

    /**
     * TranslationService constructor.
     * @param ITranslationRepository $translationRepository
     * @param BlacklistService $blacklistService
     * @param ITransactionManager $transactionManager
     * @param EntityExistsRepository $entityExistsRepository
     */
    public function __construct(ITranslationRepository $translationRepository,
                                BlacklistService $blacklistService,
                                EntityExistsRepository $entityExistsRepository)
    {
        $this->translationRepository = $translationRepository;
        $this->blacklistService = $blacklistService;
        $this->entityExistsRepository = $entityExistsRepository;
    }

    /**
     * @param TranslationDto[] $translationDtos
     * @return array
     * @throws \Exception
     */
    public function upsert(array $translationDtos)
    {
        /** @var Translation[] $translations */
        $translations = array_map([TranslationDto::class, 'toTranslation'], $translationDtos);

        $this->upsertTranslationEntities($translations);

        return array_map([TranslationDto::class, 'fromTranslationEntity'], $translations);
    }

    /**
     * @param Translation[] $translations
     * @return void
     */
    public function upsertTranslationEntities(array $translations)
    {
        $translationsKeys = array_map(fn(ITranslationEntity $translation): ITranslationKey => $translation->getTranslationKey(), $translations);
        $translationsInDatabase = $this->translationRepository->findByKeys($translationsKeys);

        foreach ($translations as $translation) {
            if ($this->inTranslationArray($translation, $translationsInDatabase)) {
                $this->translationRepository->update($translation);
            } else {
                $this->translationRepository->create($translation);
            }
        }

        $blacklistKeys = array_map([TranslationMapper::class, 'entityToBlacklistKeys'], $translations);
        $this->blacklistService->insertNew($blacklistKeys);
    }

    /**
     * @param ITranslationEntity $translation
     * @param ITranslationEntity[] $translationsArray
     * @return bool
     */
    private function inTranslationArray(ITranslationEntity $translation, array $translationsArray)
    {
        foreach ($translationsArray as $translationInBb) {
            if ($translation->getTranslationKey() == $translationInBb->getTranslationKey()) {
                return true;
            }
        }

        return false;
    }

    public function search(array $translationKeyDtos)
    {
        $translationKeys = array_map([TranslationKeyDto::class, 'toTranslationKey'], $translationKeyDtos);
        $notValidKeys = array_filter($translationKeys, [new TranslationValidator($this->entityExistsRepository), "validate"]);

        if (!empty($notValidKeys)) {
            $notValidKeys = implode(', ', array_map(fn(ITranslationKey $translationKey): string => $translationKey->getEntityId(), $notValidKeys));
            throw new \InvalidArgumentException("Entities with ids {$notValidKeys} does not exists");
        }

        $result = $this->translationRepository->findByKeys($translationKeys);

        return array_map([TranslationDto::class, 'fromTranslationEntity'], $result);
    }

    /**
     * @return array
     */
    public function getTranslationEntities()
    {
        return array_map([TranslationEntityDto::class, 'create'], TranslationEntity::keys());
    }
}