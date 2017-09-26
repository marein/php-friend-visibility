<?php

namespace Marein\FriendVisibility;

/**
 * This singleton is an application wide configuration to enable strict checks against friends.
 */
final class FriendConfiguration
{
    /**
     * @var FriendConfiguration
     */
    private static $instance = null;

    /**
     * @var bool
     */
    private $isProductionModeEnabled;

    // This class is not newable.
    private function __construct()
    {
        $this->isProductionModeEnabled = false;
    }

    // This class is not cloneable.
    private function __clone()
    {
    }

    /**
     * Get an instance of FriendConfiguration.
     *
     * @return FriendConfiguration
     */
    public static function instance(): FriendConfiguration
    {
        if (self::$instance === null) {
            self::$instance = new FriendConfiguration();
        }

        return self::$instance;
    }

    /**
     * Enable the production mode. Properties and methods are accessible from everywhere. This breaks encapsulation.
     */
    public function enableProductionMode(): void
    {
        $this->isProductionModeEnabled = true;
    }

    /**
     * Disable the production mode. Properties and methods are only accessible from friend classes.
     */
    public function disableProductionMode(): void
    {
        $this->isProductionModeEnabled = false;
    }

    /**
     * Returns true if the production mode is enabled, false otherwise.
     *
     * @return bool
     */
    public function isProductionModeEnabled(): bool
    {
        return $this->isProductionModeEnabled;
    }
}