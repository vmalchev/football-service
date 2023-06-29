<?php
namespace Sportal\FootballApi\Model;

trait UpdateTimestamp
{

    /**
     * Timestamp when the entry was last updated
     * @var \DateTime
     */
    private $updatedAt;

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedIndex()
    {
        if ($this->updatedAt !== null) {
            return [
                'updated_at' => $this->updatedAt
            ];
        }
        return [];
    }

    public function jsonUpdatedAt()
    {
        if ($this->updatedAt !== null) {
            return [
                'updated_at' => $this->updatedAt->format(\DateTime::ATOM)
            ];
        }
        return [];
    }
}