<?php

namespace Sportal\FootballApi\Domain\LiveCommentary;



class LiveCommentaryModel
{
    /**
     * @var ILiveCommentaryEntity
     */
    private $entity;

    public function __construct(
        ILiveCommentaryEntity $entity
    ) {
        $this->entity  = $entity;
    }

    /**
     * @return string|null
     */
    public function generateAutoText()
    {
        $autoText = $this->getEntity()->getTemplateText();
        foreach ($this->entity->getDetails() as $detail) {
            $autoText = $this->replace($detail->getPlaceholder(), $detail->getValue(), $autoText);
        }

        return $autoText;
    }

    /**
     * @param ILiveCommentaryEntity $entity
     */
    public function setEntity(ILiveCommentaryEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return ILiveCommentaryEntity
     */
    public function getEntity(): ILiveCommentaryEntity
    {
        return $this->entity;
    }

    private function replace($placeholder, $replace, $autoText)
    {
        return str_replace(
            '[' . $placeholder . ']',
            $replace,
            $autoText
        );
    }

    public function __clone()
    {
        $this->entity = clone $this->entity;
    }
}