<?php


namespace Sportal\FootballApi\Service;


use Sportal\FootballApi\Dto\IDto;

interface IService
{
    public function process(IDto $inputDto);
}