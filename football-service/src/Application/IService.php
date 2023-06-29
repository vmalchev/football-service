<?php


namespace Sportal\FootballApi\Application;


interface IService
{
    public function process(IDto $inputDto);
}