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
        // Include the trait for php magic methods. The magic methods make use of self::friendClasses().
        // Take a look below how this is implemented.
        use HasFriendClasses;

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

        // This method exists just for the illustration that you can also access private methods.
        private function changeLight(Light $light): void
        {
            $this->light = $light;
        }

        // This method exists just for the illustration that you can also access private static methods. This surely
        // not belongs here.
        private static function say($string): void
        {
            echo $string;
        }

        // This function is a MUST HAVE if you use the trait. The trait will use it to determine if the caller is
        // one of these classes. Only these classes have access to private and protected properties and methods.
        protected static function friendClasses(): array
        {
            return [
                RedLight::class,
                RedYellowLight::class,
                YellowLight::class,
                GreenLight::class
            ];
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
            // Access the private property.
            $trafficLight->light = new YellowLight();
            // Access the private static method.
            $trafficLight::say('The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL);
        }
    }

    class YellowLight implements Light
    {
        public function switchLight(TrafficLight $trafficLight): void
        {
            // Access the private method.
            $trafficLight->changeLight(new RedLight());
            // Access the private static method.
            $trafficLight::say('The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL);
        }
    }

    class RedLight implements Light
    {
        public function switchLight(TrafficLight $trafficLight): void
        {
            // Access the private property.
            $trafficLight->light = new RedYellowLight();
            // Access the private static method.
            $trafficLight::say('The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL);
        }
    }

    class RedYellowLight implements Light
    {
        public function switchLight(TrafficLight $trafficLight): void
        {
            // Access the private method.
            $trafficLight->changeLight(new GreenLight());
            // Access the private static method.
            $trafficLight::say('The traffic light turns to ' . get_class($trafficLight->light) . '.' . PHP_EOL);
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

            // If you enable the next line, an exception will be thrown. This class has no privileges to
            // access the private method.
//            $trafficLight->changeLight(new RedLight());
        }
    }

    $tryer = new TryToAccessTrafficLight();
    $tryer->accessTrafficLight($trafficLight);
}