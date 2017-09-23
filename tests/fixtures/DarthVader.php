<?php

final class DarthVader
{
    public function cutOffHandFromLuke(Luke $luke): bool
    {
        // This will succeed.
        $luke->cutOffHand();

        return $luke->hasRightHand;
    }

    public function tellLukeImYourDaddy(Luke $luke): string
    {
        // This will succeed.
        $luke->daddy = 'Darth Vader';

        return $luke->daddy;
    }

    public function watchHairColor(Luke $luke): string
    {
        // This will succeed.
        return $luke::hairColor();
    }
}