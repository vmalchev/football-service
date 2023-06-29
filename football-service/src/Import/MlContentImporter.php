<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Repository\LanguageRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\MlContentRepository;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballFeedCommon\MlContentInterface;

class MlContentImporter
{

    /**
     *
     * @var MlContentRepository
     */
    private $repository;

    /**
     *
     * @var \Sportal\FootballFeedCommon\MlContentFeedInterface[]
     */
    private $feeds;

    /**
     *
     * @var MappingRepositoryContainer
     */
    private $mappings;

    /**
     *
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var IBlacklistRepository
     */
    private $blacklistRepository;

    /**
     * @var MlContentInterface
     */
    private MlContentInterface $mlContent;

    private JobDispatcherInterface $dispatcher;


    /**
     * MlContentImporter constructor.
     *
     * @param MlContentRepository $repository
     * @param array $feeds
     * @param MappingRepositoryContainer $mappings
     * @param LoggerInterface $logger
     * @param LanguageRepository $languageRepository
     * @param IBlacklistRepository $blacklistRepository
     * @param MlContentInterface $mlContent
     */
    public function __construct(MlContentRepository $repository, array $feeds, MappingRepositoryContainer $mappings,
                                LoggerInterface $logger, LanguageRepository $languageRepository, IBlacklistRepository $blacklistRepository,
                                MlContentInterface $mlContent, JobDispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->feeds = $feeds;
        $this->mappings = $mappings;
        $this->logger = $logger;
        $this->languageRepository = $languageRepository;
        $this->blacklistRepository = $blacklistRepository;
        $this->mlContent = $mlContent;
        $this->dispatcher = $dispatcher;
    }

    public function importData(array $content, $className, $objectId, $languageCode)
    {
        $primaryKey = [
            'entity' => NameUtil::persistanceName($className),
            'entity_id' => $objectId,
            'language_code' => $languageCode
        ];

        $blacklistKey = new BlacklistKey(
            BlacklistType::TRANSLATION(),
            new BlacklistEntityName(NameUtil::persistanceName($className)),
            $objectId,
            $languageCode
        );

        if ($this->blacklistRepository->exists($blacklistKey)) {
            return;
        }

        $mlContent = $this->repository->createObject(
            array_merge($primaryKey, [
                'content' => $content
            ]));
        $existing = $this->repository->find($primaryKey);

        if ($existing === null) {
            $this->repository->create($mlContent);
            $this->logger->info(
                NameUtil::shortClassName(get_class($this)) . ": Created " . NameUtil::shortClassName($className) . " " .
                $primaryKey['entity'] . "-" . $primaryKey['entity_id'] . "-" . $primaryKey['language_code']);
        } else {
            $this->repository->update($mlContent);
            $this->logger->info(
                NameUtil::shortClassName(get_class($this)) . ": Updated " . NameUtil::shortClassName($className) . " " .
                $primaryKey['entity'] . "-" . $primaryKey['entity_id'] . "-" . $primaryKey['language_code']);
        }
    }

    public function import($className, $objectId, array $languages = null)
    {
        $languageCodes = empty($languages) ? $this->languageRepository->getLanguageCodes() : $languages;
        foreach ($languageCodes as $languageCode) {
            foreach ($this->feeds as $source => $feed) {
                if ($feed->supports($className, $languageCode) &&
                    ($feedId = $this->mappings->get($source)->getRemoteId($className, $objectId)) !== null) {
                    $content = $feed->getContent($className, $feedId, $languageCode);
                    if ($content !== null) {
                        $this->importData($content, $className, $objectId, $languageCode);
                    }
                }
            }
        }
    }

    public function importUpdatedAfter(\DateTimeInterface $afterTime)
    {
        foreach ($this->feeds as $source => $feed) {
            $remoteTranslationData = $this->mlContent->pullTranslations($afterTime);
            foreach ($remoteTranslationData as $item) {
                $ownId = $this->mappings->get($source)->getOwnId($item['class_name'], $item['id']);
                $this->dispatcher->dispatch('Import\MlContentObject', [
                    $item['class_name'],
                    $ownId
                ]);
            }
        }
    }
}