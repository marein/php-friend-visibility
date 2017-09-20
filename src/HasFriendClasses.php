<?php

namespace Marein\FriendVisibility;

trait HasFriendClasses
{
    public function __get($name)
    {
        return $this->executeIfTheCallerIsFriend(function () use ($name) {
            return $this->$name;
        }, function () use ($name) {
            throw new FriendException(
                sprintf(
                    'Cannot access property %s::$%s',
                    get_class($this),
                    $name
                )
            );
        });
    }

    public function __set($name, $value)
    {
        $this->executeIfTheCallerIsFriend(function () use ($name, $value) {
            $this->$name = $value;
        }, function () use ($name) {
            throw new FriendException(
                sprintf(
                    'Cannot access property %s::$%s',
                    get_class($this),
                    $name
                )
            );
        });
    }

    private function executeIfTheCallerIsFriend(callable $if, callable $else)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        // The information is stored in the third index.
        if (count($trace) > 2) {
            $callerClass = $trace[2]['class'];

            if (in_array($callerClass, self::FRIEND_CLASSES)) {
                return $if();
            }
        }

        $else();
    }
}