<?php


namespace Sportal\FootballApi\Domain\President;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Translation\ITranslationEntity;
use Sportal\FootballApi\Domain\Translation\ITranslationKey;
use Sportal\FootballApi\Domain\Translation\ITranslationRepository;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Domain\Translation\TranslationMapper;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;

class PresidentModel implements IPresidentModel
{
    private IPresidentEntity  $presidentEntity;

    private IPresidentRepository $presidentRepository;

    private ITranslationRepository $translationRepository;

    private IBlacklistRepository $blacklistRepository;

    /**
     * PresidentModel constructor.
     * @param IPresidentRepository $presidentRepository
     * @param ITranslationRepository $translationRepository
     * @param IBlacklistRepository $blacklistRepository
     */
    public function __construct(IPresidentRepository $presidentRepository, ITranslationRepository $translationRepository, IBlacklistRepository $blacklistRepository)
    {
        $this->presidentRepository = $presidentRepository;
        $this->translationRepository = $translationRepository;
        $this->blacklistRepository = $blacklistRepository;
    }

    public function getPresident(): IPresidentEntity
    {
        return $this->presidentEntity;
    }

    public function setPresident(IPresidentEntity $presidentEntity): IPresidentModel
    {
        $model = clone $this;
        $model->presidentEntity = $presidentEntity;
        return $model;
    }

    public function save(): IPresidentModel
    {
        $president = $this->presidentRepository->insert($this->presidentEntity);
        return $this->setPresident($president);
    }

    public function update(): IPresidentModel
    {
        $president = $this->presidentRepository->update($this->presidentEntity);
        return $this->setPresident($president);
    }

    public function searchUpdate(): IPresidentModel
    {
        // TODO remove this code and use search model to update
        $translationKey = new TranslationKey(TranslationEntity::PRESIDENT(), $this->presidentEntity->getId(), 'en');
        $translationContent = new TranslationContent($this->presidentEntity->getName());
        $updatedAt = new \DateTime();
        $translationEntity = new Translation($translationKey, $translationContent, $updatedAt);

        $translationsKeys = array_map(fn(ITranslationEntity $translation): ITranslationKey => $translation->getTranslationKey(), [$translationEntity]);
        $translationsInDatabase = $this->translationRepository->findByKeys($translationsKeys);

        if (empty($translationsInDatabase)) {
            $this->translationRepository->create($translationEntity);
        } else {
            $this->translationRepository->update($translationEntity);
        }

        $blacklistKeys = array_map([TranslationMapper::class, 'entityToBlacklistKeys'], [$translationEntity]);
        $blacklists = $this->blacklistRepository->findByKeys($blacklistKeys);

        if (empty($blacklists)) {
            $this->blacklistRepository->insertAll($blacklistKeys);
        }

        return $this;
    }

    public function blacklist(): IPresidentModel
    {
        // TODO remove this code and use blacklist model to update
        $blacklistKey = new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::PRESIDENT(), $this->presidentEntity->getId());
        $blacklists = $this->blacklistRepository->findByKeys([$blacklistKey]);

        if (empty($blacklists)) {
            $this->blacklistRepository->insertAll([$blacklistKey]);
        }

        return $this;
    }
}