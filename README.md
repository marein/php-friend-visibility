# php-friend-visibility

__Table of contents__

* [Foreword / Why this package?](#foreword--why-this-package)
* [Installation](#installation)
* [Usage](#usage)
* [Performance](#performance)

## Foreword / Why this package?
I love php. But sometimes I miss some features I know from other languages like CSharp, Java or C++.
These features are, for example, friend classes, package visibility and so on.
Here is a [rfc](https://wiki.php.net/rfc/friend-classes)
for [friend classes]((https://wiki.php.net/rfc/friend-classes)). I would love to see this or some package
visibility in php. Hopefully someday.

Be aware of, that friend classes breaks encapsulation. If you change the class which has friends,
you must check your friends if something has changed they use. If you rely on reflection to access private properties, this
becomes a nightmare, because the code is accessed from everywhere. Friends on the other side, are well documented in code.
Another good example for explicit code vs. reflection is the visitor pattern. You can implement a visitor-pattern–like
implementation with reflection but this is not expressive. If you add another subclass to your tree,
you have to know which classes you have to change. With the real visitor pattern, you implement an interface which gets
extended if you throw a new subclass into the match. Now, you can clearly see which visitor you have to change and implement
the new method for the new subclass.

So, hopefully someday we get friend classes.

Please reconsider your design before you use this package. It's easy to break encapsulation,
but it's hard to do object oriented programming properly. Even if you can easily add this package to your toolkit,
everyone in your team should agree. It's not a language feature, and someone may don't know what friend classes are
or what your design intention is. __As with everything: Think before you use it!__

Last but not least: The idea how this is implemented is not mine.
It's a copy from [Patrick van Bergen](http://techblog.procurios.nl/k/news/view/49401/14863/friend-classes-in-php.html)
(big thanks). I used to use a trait instead of a base class for the friendship behaviour.
The whole article is worth reading. The implementation is similar to mine.

I've created this repository because I want an easy installation.

## Installation

```
composer require marein/php-friend-visiblity
```

## Usage

Take a look at the [example](examples/has_friend_classes_example.php).
It's an implementation of the state pattern.

## Performance

To access private and protected members is of course slower than direct calls, because of the black magic.
This package use \[[debug_backtrace](http://php.net/manual/en/function.debug-backtrace.php),
[__get](http://php.net/manual/de/language.oop5.overloading.php#object.get),
[__set](http://php.net/manual/de/language.oop5.overloading.php#object.set),
[__call](http://php.net/manual/de/language.oop5.overloading.php#object.call),
[__callStatic](http://php.net/manual/de/language.oop5.overloading.php#object.callstatic)\].
You may should do a performance test for your project. However, you can enable the production mode with

```php
<?php

\Marein\FriendVisibility\FriendConfiguration::instance()->enableProductionMode();
```

The code should run much faster now. This is because all checks are disabled. Everything is now public in the classes
which use the trait. Because of that, this should not be enabled in development.

| Test case                               | Time in milliseconds |
|-----------------------------------------|----------------------|
| Access one public method                | 0,003 ms             |
| Access one private method               | 0,006 ms             |
| Access one private method in production | 0,004 ms             |