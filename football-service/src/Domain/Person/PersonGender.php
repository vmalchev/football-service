<?php

namespace Sportal\FootballApi\Domain\Person;

use MyCLabs\Enum\Enum;

/**
 * @method static PersonGender MALE()
 * @method static PersonGender FEMALE()
 */
class PersonGender extends Enum
{

    const MALE = 'MALE';
    const FEMALE = 'FEMALE';

}