<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\LocationBundle\Entity\Region;
use Chaplean\Bundle\LocationBundle\Repository\RegionRepository;
use Chaplean\Bundle\LocationBundle\Utility\DepartmentUtility;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class DepartmentUtilityTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     10.0.0
 */
class DepartmentUtilityTest extends FunctionalTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\DepartmentUtility::loadDepartment()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testLoadRegion()
    {
        /** @var RegistryInterface|MockInterface $doctrine */
        $doctrine = \Mockery::mock(Registry::class);
        $manager = \Mockery::mock(EntityManager::class);
        $regionRepo = \Mockery::mock(RegionRepository::class);
        $region = \Mockery::mock(Region::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($manager);
        $manager->shouldReceive('getRepository')->once()->with('ChapleanLocationBundle:Region')->andReturn($regionRepo);
        $regionRepo->shouldReceive('findOneBy')->times(109)->andReturn($region);
        $manager->shouldReceive('persist')->times(109)->andReturnNull();

        $regionUtility = new DepartmentUtility($doctrine);

        $regionUtility->loadDepartment();
    }
}
