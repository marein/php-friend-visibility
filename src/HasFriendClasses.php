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

    public function __call($name, $arguments)
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

    public static function __callStatic($name, $arguments)
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

    public function __get($name)
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

    public function __set($name, $value)
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