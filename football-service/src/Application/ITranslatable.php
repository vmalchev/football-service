<?php


namespace Sportal\FootballApi\Application;


use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;

interface ITranslatable
{
    public function getTranslationEntityType(): TranslationEntity;

    public function setTranslation(ITranslationContent $translationContent): void;

    public function getId(): ?string;
}