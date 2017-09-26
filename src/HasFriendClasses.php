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
        self::throwExceptionIfNotCallable(
            'Cannot access method %s::%s() from context \'%s\'',
            $name
        );

        return $this->$name(...$arguments);
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
        self::throwExceptionIfNotCallable(
            'Cannot access method %s::%s() from context \'%s\'',
            $name
        );

        return self::$name(...$arguments);
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
        self::throwExceptionIfNotCallable(
            'Cannot access property %s::$%s from context \'%s\'',
            $name
        );

        return $this->$name;
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
        self::throwExceptionIfNotCallable(
            'Cannot access property %s::$%s from context \'%s\'',
            $name
        );

        $this->$name = $value;
    }

    /**
     * Throw an exception if not in development mode and the caller is not a friend class.
     *
     * @param string $message
     * @param string $member
     *
     * @throws FriendException
     */
    private static function throwExceptionIfNotCallable(string $message, string $member): void
    {
        if (FriendConfiguration::instance()->isDebugModeEnabled()) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $callerClass = $trace[2]['class'] ?? '';

            if (!$callerClass || !in_array($callerClass, self::friendClasses())) {
                throw new FriendException(
                    sprintf(
                        $message,
                        __CLASS__,
                        $member,
                        $callerClass
                    )
                );
            }
        }
    }
}