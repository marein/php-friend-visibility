<?php

/**
 * This example is based on a simple state machine that represents a traffic light.
 * Sure, you can return the next state from the switchLight function and if your goal is
 * an implementation like this, you SHOULD return the next state.
 * This examples shows the use of this library. Documentation is removed, because it's to verbose.
 */

namespace {

    use Marein\FriendVisibility\HasFriendClasses;

    require_once __DIR__ . '/../vendor/autoload.php';

    class TrafficLight
    {
        // Include the trait for php magic methods.
        use HasFriendClasses;

        // This constant is a MUST have if you use the trait. The trait will use it to determine if the caller is
        // one of these classes. Only these classes have access to private properties and methods.
        private const FRIEND_CLASSES = [
            RedLight::class,
            RedYellowLight::class,
            YellowLight::class,
            GreenLight::class
        ];

        private $light;

        public function __construct()
        {
            $this->light = new RedLight();
            echo 'The traffic light is ' . RedLight::class . '.' . PHP_EOL;
        }

        public function switchLight(): void
        {
            $this->light->switchLight($this);
        }
    }

    interface Light
    {
        public function switchLight(TrafficLight $trafficLight): void;
    }

    class GreenLight implements Light
    {
        public function switchLight(TrafficLight $trafficLight): void
        {
            $trafficLight->light = new YellowLight();
            echo 'The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL;
        }
    }

    class YellowLight implements Light
    {
        public function switchLight(TrafficLight $trafficLight): void
        {
            $trafficLight->light = new RedLight();
            echo 'The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL;
        }
    }

    class RedLight implements Light
    {
        public function switchLight(TrafficLight $trafficLight): void
        {
            $trafficLight->light = new RedYellowLight();
            echo 'The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL;
        }
    }

    class RedYellowLight implements Light
    {
        public function switchLight(TrafficLight $trafficLight): void
        {
            $trafficLight->light = new GreenLight();
            echo 'The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL;
        }
    }

    $trafficLight = new TrafficLight();
    $trafficLight->switchLight();
    $trafficLight->switchLight();
    $trafficLight->switchLight();
    $trafficLight->switchLight();
    $trafficLight->switchLight();
    $trafficLight->switchLight();
    $trafficLight->switchLight();
    $trafficLight->switchLight();

    class TryToAccessTrafficLight
    {
        public function accessTrafficLight(TrafficLight $trafficLight): void
        {
            // If you enable the next line, an exception will be thrown. This class has no privileges to
            // access the private property.
//            $trafficLight->light;
        }
    }

    $tryer = new TryToAccessTrafficLight();
    $tryer->accessTrafficLight($trafficLight);
}