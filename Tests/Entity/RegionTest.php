<?php

namespace Chaplean\Bundle\LocationBundle\Tests\Entity;

use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\LocationBundle\Entity\Region;
use Chaplean\Bundle\UnitBundle\Test\LogicalTest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

/**
 * CityTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class RegionTest extends LogicalTest
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->serializer = $this->getContainer()->get('jms_serializer');
    }

    /**
     * @return void
     */
    public function testRegion()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $this->assertEquals('SuperRegion', $region->getName());
        $this->assertEquals('05', $region->getCode());
    }

    /**
     * @return void
     */
    public function testRegionSerializerWithoutGroup()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $regionSerialized = $this->serializer->serialize($region, 'json');

        $this->assertEquals(array(
            'name' => 'SuperRegion',
            'code' => '05',
            'departments' => array(),
            'dtype' => 'region',
        ), json_decode($regionSerialized, true));
    }

    /**
     * @return void
     */
    public function testRegionSerializerWithGroupName()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $regionSerialized = $this->serializer->serialize($region, 'json', SerializationContext::create()->setGroups(array('region_name')));

        $this->assertEquals(array(
            'name' => 'SuperRegion'
        ), json_decode($regionSerialized, true));
    }

    /**
     * @return void
     */
    public function testRegionSerializerWithGroup()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $regionSerialized = $this->serializer->serialize($region, 'json', SerializationContext::create()->setGroups(array('region_name', 'region_code')));

        $this->assertEquals(array(
            'name' => 'SuperRegion',
            'code' => '05',
        ), json_decode($regionSerialized, true));
    }

    /**
     * @return void
     */
    public function testRegionDepartments()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $department = new Department();
        $region->addDepartment($department);

        $this->assertCount(1, $region->getDepartments());

        $region->removeDepartment($department);

        $this->assertCount(0, $region->getDepartments());
    }
}
