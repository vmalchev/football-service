<?php

namespace Sportal\FootballApi\Domain\Translation;


interface ITranslationEntity
{
    /**
     * @return ITranslationKey
     */
    public function getTranslationKey(): ITranslationKey;

    /**
     * @return ITranslationContent
     */
    public function getContent(): ITranslationContent;
}