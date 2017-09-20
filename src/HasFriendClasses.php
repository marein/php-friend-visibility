<?php

namespace Marein\FriendVisibility;

trait HasFriendClasses
{
    public function __call($name, $arguments)
    {
        return $this->executeIfTheCallerIsFriend(function () use ($name, $arguments) {
            $this->$name(...$arguments);
        }, function ($context) use ($name) {
            throw new FriendException(
                sprintf(
                    'Cannot access method %s::%s() from context \'%s\'',
                    get_class($this),
                    $name,
                    $context
                )
            );
        });
    }

    public static function __callStatic($name, $arguments)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $context = '';

        // The information is stored in the third index.
        if (count($trace) > 1) {
            $callerClass = $context = $trace[1]['class'];

            if (in_array($callerClass, self::FRIEND_CLASSES)) {
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
        return $this->executeIfTheCallerIsFriend(function () use ($name) {
            return $this->$name;
        }, function ($context) use ($name) {
            throw new FriendException(
                sprintf(
                    'Cannot access property %s::$%s from context \'%s\'',
                    get_class($this),
                    $name,
                    $context
                )
            );
        });
    }

    public function __set($name, $value)
    {
        $this->executeIfTheCallerIsFriend(function () use ($name, $value) {
            $this->$name = $value;
        }, function ($context) use ($name) {
            throw new FriendException(
                sprintf(
                    'Cannot access property %s::$%s from context \'%s\'',
                    get_class($this),
                    $name,
                    $context
                )
            );
        });
    }

    private function executeIfTheCallerIsFriend(callable $if, callable $else)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $context = '';

        // The information is stored in the third index.
        if (count($trace) > 2) {
            $callerClass = $context = $trace[2]['class'];

            if (in_array($callerClass, self::FRIEND_CLASSES)) {
                return $if();
            }
        }

        $else($context);
    }
}