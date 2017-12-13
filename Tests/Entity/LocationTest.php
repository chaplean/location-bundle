<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Entity;

use Chaplean\Bundle\LocationBundle\Entity\Location;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class LocationTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\DataFixtures\ORM
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     8.0.0
 */
class LocationTest extends MockeryTestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Location::getId()
     *
     * @return void
     */
    public function testGetters()
    {
        /** @var Location $location */
        $location = \Mockery::mock(Location::class, [])->makePartial();

        $this->assertNull($location->getId());
    }
}
