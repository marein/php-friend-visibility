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
    private $debugMode;

    // This class is not newable.
    private function __construct()
    {
        $this->debugMode = true;
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
     * Enable the debug mode. Properties and methods are only accessible from friend classes.
     */
    public function enableDebugMode(): void
    {
        $this->debugMode = true;
    }

    /**
     * Disable the debug mode. Properties and methods are accessible from everywhere.
     */
    public function disableDebugMode(): void
    {
        $this->debugMode = false;
    }

    /**
     * Returns true if the debug mode is enabled, false otherwise.
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->debugMode;
    }
}