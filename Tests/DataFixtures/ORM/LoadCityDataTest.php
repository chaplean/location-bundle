<?php

namespace Tests\Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadCityData;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class LoadCityDataTest.
 *
 * @package   Chaplean\Bundle\LocationBundle\Tests\DataFixtures\ORM
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     6.0.1
 */
class LoadCityDataTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadCityData::load()
     *
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testLoad36922Cities()
    {
        $objectManager = \Mockery::mock(ObjectManager::class);
        $objectManager->shouldReceive('persist')
            ->times(36922);
        $objectManager->shouldReceive('flush')
            ->once();

        /**
         * @var $loadCityData \PHPUnit_Framework_MockObject_MockObject|LoadCityData
         */
        $loadCityData = $this->getMockBuilder(LoadCityData::class)
            ->setMethods(['getReference', 'setReference'])
            ->getMock();
        $loadCityData->expects($this->any())
            ->method('getReference');
        $loadCityData->expects($this->any())
            ->method('setReference');

        $loadCityData->load($objectManager);

        \Mockery::close();
    }
}
