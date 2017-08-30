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
         * @var $loadCityData \Mockery\MockInterface|LoadCityData
         */
        $loadCityData = \Mockery::mock(LoadCityData::class)
            ->makePartial();
        $loadCityData->shouldReceive('getReference');
        $loadCityData->shouldReceive('setReference');

        $loadCityData->load($objectManager);

        \Mockery::close();
    }
}
