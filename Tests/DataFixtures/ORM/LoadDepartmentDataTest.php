<?php

namespace Tests\Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadDepartmentData;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class LoadDepartmentDataTest.
 *
 * @package   Chaplean\Bundle\LocationBundle\Tests\DataFixtures\ORM
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     6.0.1
 */
class LoadDepartmentDataTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadDepartmentData::load()
     *
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testLoad109Departments()
    {
        $objectManager = \Mockery::mock(ObjectManager::class);
        $objectManager->shouldReceive('persist')
            ->times(109);
        $objectManager->shouldReceive('flush')
            ->once();

        /**
         * @var $loadDepartmentData \Mockery\MockInterface|LoadDepartmentData
         */
        $loadDepartmentData = \Mockery::mock(LoadDepartmentData::class)
            ->makePartial();
        $loadDepartmentData->shouldReceive('getReference');
        $loadDepartmentData->shouldReceive('setReference');

        $loadDepartmentData->load($objectManager);

        \Mockery::close();
    }
}
