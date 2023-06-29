<?php

namespace Sportal\FootballApi\Domain\Translation;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Infrastructure\Translation\Translation;

class TranslationMapper
{
    /**
     * @param $translation
     * @return IBlacklistKey
     */
    public static function entityToBlacklistKeys(Translation $translation): IBlacklistKey
    {
        return new BlacklistKey(
            new BlacklistType('translation'),
            BlacklistEntityName::{$translation->getTranslationKey()->getEntity()->getKey()}(),
            $translation->getTranslationKey()->getEntityId(),
            $translation->getTranslationKey()->getLanguage()
        );
    }
}