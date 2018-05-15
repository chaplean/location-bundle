<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\LocationBundle\Utility\RegionUtility;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Class RegionUtilityTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     10.0.0
 */
class RegionUtilityTest extends FunctionalTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\RegionUtility::loadRegion()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testLoadRegion()
    {
        $doctrine = \Mockery::mock(Registry::class);
        $manager = \Mockery::mock(EntityManager::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($manager);
        $manager->shouldReceive('persist')->times(19)->andReturnNull();

        $regionUtility = new RegionUtility($doctrine);

        $regionUtility->loadRegion();
    }
}
