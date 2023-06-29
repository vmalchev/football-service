<?php

namespace Sportal\FootballApi\Domain\Translation;


interface ITranslationContent
{
    public function getName(): ?string;

    public function getThreeLetterCode(): ?string;

    public function getShortName(): ?string;

    public function toArray(): array;
}