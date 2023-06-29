<?php


namespace Sportal\FootballApi\Domain\President;


interface IPresidentRepository
{
    /**
     * @param string $id
     * @return IPresidentEntity|null
     */
    public function findById(string $id): ?IPresidentEntity;

    /**
     * @param IPresidentEntity $president
     * @return IPresidentEntity
     */
    public function insert(IPresidentEntity $president): IPresidentEntity;

    /**
     * @param IPresidentEntity $president
     * @return IPresidentEntity
     */
    public function update(IPresidentEntity $president): IPresidentEntity;

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool;

    /**
     * @param string $name
     * @return IPresidentEntity|null
     */
    public function findByName(string $name): ?IPresidentEntity;
}