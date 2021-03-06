<?php

namespace Marein\FriendVisibility;

use PHPUnit\Framework\TestCase;

class HasFriendClassesTest extends TestCase
{
    /**
     * Load required classes.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Load fixtures.
        require_once __DIR__ . '/../fixtures/DarthVader.php';
        require_once __DIR__ . '/../fixtures/Lea.php';
        require_once __DIR__ . '/../fixtures/Luke.php';
    }

    /**
     * @test
     */
    public function itShouldBePossibleToAccessPrivateMethods(): void
    {
        $luke = new \Luke();
        $darthVader = new \DarthVader();

        $this->assertFalse($darthVader->cutOffHandFromLuke($luke));
    }

    /**
     * @test
     */
    public function itShouldBePossibleToAccessPrivateProperties(): void
    {
        $luke = new \Luke();
        $darthVader = new \DarthVader();

        $this->assertEquals(
            'Darth Vader',
            $darthVader->tellLukeImYourDaddy($luke)
        );
    }

    /**
     * @test
     */
    public function itShouldBePossibleToAccessPrivateStaticMethods(): void
    {
        $luke = new \Luke();
        $darthVader = new \DarthVader();

        $this->assertEquals(
            'blonde',
            $darthVader->watchHairColor($luke)
        );
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToAccessPrivateMethodsIfNoFriend(): void
    {
        $this->expectException(FriendException::class);

        $luke = new \Luke();
        $lea = new \Lea();

        $this->assertFalse($lea->cutOffHandFromLuke($luke));
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToAccessPrivatePropertiesIfNoFriend(): void
    {
        $this->expectException(FriendException::class);

        $luke = new \Luke();
        $lea = new \Lea();

        $this->assertEquals(
            'Lea',
            $lea->tellLukeImYourDaddy($luke)
        );
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToAccessPrivateStaticMethodsIfNoFriend(): void
    {
        $this->expectException(FriendException::class);

        $luke = new \Luke();
        $lea = new \Lea();

        $this->assertEquals(
            'blonde',
            $lea->watchHairColor($luke)
        );
    }

    /**
     * @test
     */
    public function everythingShouldBeAccessibleInProductionMode(): void
    {
        FriendConfiguration::instance()->enableProductionMode();

        $luke = new \Luke();
        $luke->daddy = 'Anakin Skywalker';
        $luke->cutOffHand();

        $this->assertEquals(
            'Anakin Skywalker',
            $luke->daddy
        );

        $this->assertFalse(
            $luke->hasRightHand
        );

        $this->assertEquals(
            'blonde',
            $luke::hairColor()
        );

        FriendConfiguration::instance()->disableProductionMode();
    }
}