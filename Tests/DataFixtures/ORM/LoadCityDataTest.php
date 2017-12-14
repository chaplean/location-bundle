<?php

namespace Tests\Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadCityData;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class LoadCityDataTest.
 *
 * @package   Chaplean\Bundle\LocationBundle\Tests\DataFixtures\ORM
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     6.0.1
 */
class LoadCityDataTest extends MockeryTestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadCityData::load()
     *
     * @return void
     */
    public function testLoad39200Cities()
    {
        self::markTestSkipped('Allowed memory size of 2147483648 bytes exhausted');

        $objectManager = \Mockery::mock(ObjectManager::class);
        $entityRepository = \Mockery::mock(EntityRepository::class);

        $entityRepository->shouldReceive('findOneBy')->times(39200)->andReturnNull();
        $entityRepository->shouldReceive('findOneBy')->once()->andReturn(new City());

        $objectManager->shouldReceive('getRepository')->once()->andReturn($entityRepository);
        $objectManager->shouldReceive('persist')->times(39200);
        $objectManager->shouldReceive('flush')->once();


        /** @var LoadCityData|\Mockery\MockInterface $loadCityData */
        $loadCityData = \Mockery::mock(LoadCityData::class)->makePartial();
        $loadCityData->shouldReceive('getReference')->andReturn(new Department());
        $loadCityData->shouldReceive('setReference');

        $loadCityData->load($objectManager);
    }
}
