<?php


namespace Sportal\FootballApi\Domain\President;


interface IPresidentModel
{
    public function getPresident(): IPresidentEntity;

    public function setPresident(IPresidentEntity $presidentEntity): IPresidentModel;

    public function save(): IPresidentModel;

    public function update(): IPresidentModel;

    public function searchUpdate(): IPresidentModel;

    public function blacklist(): IPresidentModel;
}