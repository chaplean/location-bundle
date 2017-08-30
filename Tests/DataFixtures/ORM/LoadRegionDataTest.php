<?php

namespace Tests\Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadRegionData;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class LoadRegionDataTest.
 *
 * @package   Chaplean\Bundle\LocationBundle\Tests\DataFixtures\ORM
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     6.0.1
 */
class LoadRegionDataTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadRegionData::load()
     *
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testLoad19Regions()
    {
        $objectManager = \Mockery::mock(ObjectManager::class);
        $objectManager->shouldReceive('persist')
            ->times(19);
        $objectManager->shouldReceive('flush')
            ->once();

        /**
         * @var $loadRegionData \PHPUnit_Framework_MockObject_MockObject|LoadRegionData
         */
        $loadRegionData = $this->getMockBuilder(LoadRegionData::class)
            ->setMethods(['getReference', 'setReference'])
            ->getMock();
        $loadRegionData->expects($this->any())
            ->method('getReference');
        $loadRegionData->expects($this->any())
            ->method('setReference');

        $loadRegionData->load($objectManager);

        \Mockery::close();
    }
}
