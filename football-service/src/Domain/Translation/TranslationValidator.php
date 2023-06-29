<?php

namespace Sportal\FootballApi\Domain\Translation;


use Sportal\FootballApi\Infrastructure\Entity\EntityExistsRepository;

class TranslationValidator
{
    private EntityExistsRepository $entityExistsRepository;

    public function __construct(
        EntityExistsRepository $entityExistsRepository
    )
    {
        $this->entityExistsRepository = $entityExistsRepository;
    }

    public function validate(ITranslationKey $keyDto)
    {
        if (!$this->entityExistsRepository->exists($keyDto->getEntity()->getValue(), $keyDto->getEntityId())) {
            return true;
        }
    }
}