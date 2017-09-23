<?php

final class Lea
{
    public function cutOffHandFromLuke(Luke $luke): bool
    {
        // This will fail.
        $luke->cutOffHand();

        return $luke->hasRightHand;
    }

    public function tellLukeImYourDaddy(Luke $luke): string
    {
        // This will fail.
        $luke->daddy = 'Lea';

        return $luke->daddy;
    }

    public function watchHairColor(Luke $luke): string
    {
        // This will fail.
        return $luke::hairColor();
    }
}