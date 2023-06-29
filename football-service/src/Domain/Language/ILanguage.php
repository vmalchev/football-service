<?php

namespace Sportal\FootballApi\Domain\Language;

interface ILanguage
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

}
