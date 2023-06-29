<?php
namespace Sportal\FootballApi\Util;

class TimeUtil
{

    /**
     *
     * @param string $string
     * @param string $defaultModifier
     * @return \DateTime
     */
    public function getDateTime($string, $defaultModifier)
    {
        $validModifier = ! empty($defaultModifier) && strtotime($defaultModifier);
        
        if (! empty($string)) {
            $dateTime = new \DateTime($string);
        } elseif ($validModifier) {
            $dateTime = new \DateTime();
            $dateTime->modify($defaultModifier);
        } else {
            throw new \InvalidArgumentException("Invalid modifier $defaultModifier and no DateTime string specified");
        }
        
        return $dateTime;
    }
}