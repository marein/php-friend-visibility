<?php

namespace Marein\FriendVisibility;

trait HasFriendClasses
{
    /**
     * Returns the friend classes, which can access private and protected properties and methods.
     *
     * @return array
     */
    abstract protected static function friendClasses(): array;

    /**
     * If the global debug mode is disabled. This method exposes all non accessible methods.
     * If the global debug mode is enabled, only friend classes returned by self::friendClasses() can
     * access non accessible methods.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     * @throws FriendException
     */
    public function __call(string $name, array $arguments)
    {
        if (!FriendConfiguration::instance()->isDebugModeEnabled()) {
            return $this->$name(...$arguments);
        }

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $context = '';

        if (count($trace) > 1) {
            $callerClass = $context = $trace[1]['class'];

            if (in_array($callerClass, self::friendClasses())) {
                return $this->$name(...$arguments);
            }
        }

        throw new FriendException(
            sprintf(
                'Cannot access method %s::%s() from context \'%s\'',
                get_class($this),
                $name,
                $context
            )
        );
    }

    /**
     * If the global debug mode is disabled. This method exposes all non accessible static methods.
     * If the global debug mode is enabled, only friend classes returned by self::friendClasses() can
     * access non accessible static methods.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     * @throws FriendException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (!FriendConfiguration::instance()->isDebugModeEnabled()) {
            return self::$name(...$arguments);
        }

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $context = '';

        if (count($trace) > 1) {
            $callerClass = $context = $trace[1]['class'];

            if (in_array($callerClass, self::friendClasses())) {
                return self::$name(...$arguments);
            }
        }

        throw new FriendException(
            sprintf(
                'Cannot access method %s::%s() from context \'%s\'',
                __CLASS__,
                $name,
                $context
            )
        );
    }

    /**
     * If the global debug mode is disabled. This method exposes all non accessible properties.
     * If the global debug mode is enabled, only friend classes returned by self::friendClasses() can
     * access non accessible properties.
     *
     * @param string $name
     *
     * @return mixed
     * @throws FriendException
     */
    public function __get(string $name)
    {
        if (!FriendConfiguration::instance()->isDebugModeEnabled()) {
            return $this->$name;
        }

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $context = '';

        if (count($trace) > 1) {
            $callerClass = $context = $trace[1]['class'];

            if (in_array($callerClass, self::friendClasses())) {
                return $this->$name;
            }
        }

        throw new FriendException(
            sprintf(
                'Cannot access property %s::$%s from context \'%s\'',
                get_class($this),
                $name,
                $context
            )
        );
    }

    /**
     * If the global debug mode is disabled. This method exposes all non accessible properties.
     * If the global debug mode is enabled, only friend classes returned by self::friendClasses() can
     * access non accessible properties.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @throws FriendException
     */
    public function __set(string $name, $value)
    {
        if (!FriendConfiguration::instance()->isDebugModeEnabled()) {
            $this->$name = $value;
            return;
        }

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $context = '';

        if (count($trace) > 1) {
            $callerClass = $context = $trace[1]['class'];

            if (in_array($callerClass, self::friendClasses())) {
                $this->$name = $value;
                return;
            }
        }

        throw new FriendException(
            sprintf(
                'Cannot access property %s::$%s from context \'%s\'',
                get_class($this),
                $name,
                $context
            )
        );
    }
}