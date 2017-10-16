<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class LocationUpgradeCitiesUtilityTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class LocationUpgradeCitiesUtilityTest extends MockeryTestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::__construct
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::isCityExisting
     *
     * @return void
     */
    public function testIsCityExisting()
    {
        $doctrine = \Mockery::mock(RegistryInterface::class);
        $em = \Mockery::mock(EntityManager::class);
        $repository = \Mockery::mock(EntityRepository::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($em);
        $em->shouldReceive('getRepository')->once()->andReturn($repository);

        $repository->shouldReceive('findOneBy')->once()->with(['name' => 'foo', 'zipcode' => 'bar'])->andReturnNull();

        $locationUpgradeCitiesUtility = new LocationUpgradeCitiesUtility($doctrine);

        $this->assertFalse($locationUpgradeCitiesUtility->isCityExisting('foo', 'bar'));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::__construct
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::getNewCities
     *
     * @return void
     */
    public function testGetNewCities()
    {
        $doctrine = \Mockery::mock(RegistryInterface::class);
        $em = \Mockery::mock(EntityManager::class);
        $repository = \Mockery::mock(EntityRepository::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($em);
        $em->shouldReceive('getRepository')->once()->andReturn($repository);

        $locationUpgradeCitiesUtility = new LocationUpgradeCitiesUtility($doctrine);

        $this->assertCount(39201, $locationUpgradeCitiesUtility->getNewCities());
    }
}
