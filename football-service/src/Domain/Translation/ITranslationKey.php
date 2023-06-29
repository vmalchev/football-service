<?php

namespace Sportal\FootballApi\Domain\Translation;


interface ITranslationKey
{
    /**
     * @return TranslationEntity
     */
    public function getEntity(): TranslationEntity;

    /**
     * @return string
     */
    public function getEntityId(): string;

    /**
     * @return string
     */
    public function getLanguage(): string;

    /**
     * Return the key data as a string representation
     * @return string
     */
    public function __toString(): string;
}