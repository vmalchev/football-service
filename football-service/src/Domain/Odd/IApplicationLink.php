<?php
namespace Sportal\FootballApi\Domain\Odd;

interface IApplicationLink
{

    function getApplicationId();

    function getFallbackLink();

    function getBetslipLink();
}