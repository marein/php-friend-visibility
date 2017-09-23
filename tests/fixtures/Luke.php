<?php

final class Luke
{
    use \Marein\FriendVisibility\HasFriendClasses;

    // Only DarthVader and Luke himself are allowed to access this property.
    private $daddy = 'unknown';

    // Only DarthVader and Luke himself are allowed to access this property.
    private $hasRightHand = true;

    // Only DarthVader and Luke himself are allowed to access this method.
    private function cutOffHand(): void
    {
        $this->hasRightHand = false;
    }

    // Only DarthVader and Luke himself are allowed to access this method.
    private static function hairColor(): string
    {
        return 'blonde';
    }

    protected static function friendClasses(): array
    {
        return [
            DarthVader::class
        ];
    }
}