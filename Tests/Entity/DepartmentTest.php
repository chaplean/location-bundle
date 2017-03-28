<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Entity;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\LocationBundle\Entity\Region;
use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

/**
 * CityTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class DepartmentTest extends LogicalTestCase
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
        parent::setUp();

        $this->serializer = $this->getContainer()->get('jms_serializer');
    }

    /**
     * @return void
     */
    public function testDepartment()
    {
        $department = new Department();

        $department->setName('SuperDepartment');
        $department->setCode('05');
        $department->setRegion(new Region());

        $this->assertEquals('SuperDepartment', $department->getName());
        $this->assertEquals('05', $department->getCode());
        $this->assertInstanceOf(Region::class, $department->getRegion());
    }

    /**
     * @return void
     */
    public function testDepartmentSerializerWithoutGroup()
    {
        $department = new Department();

        $department->setName('SuperDepartment');
        $department->setCode('05');
        $department->setRegion(new Region());

        $departmentSerialized = $this->serializer->serialize($department, 'json');

        $this->assertEquals(array(
            'name' => 'SuperDepartment',
            'code' => '05',
            'cities' => array(),
            'region' => array(
                'departments' => array(),
                'dtype' => 'region'
            ),
            'dtype' => 'department',
        ), json_decode($departmentSerialized, true));
    }

    /**
     * @return void
     */
    public function testDepartmentSerializerWithGroupName()
    {
        $department = new Department();

        $department->setName('SuperDepartment');
        $department->setCode('05');
        $department->setRegion(new Region());

        $departmentSerialized = $this->serializer->serialize($department, 'json', SerializationContext::create()->setGroups(array('location_name')));

        $this->assertEquals(array(
            'name' => 'SuperDepartment'
        ), json_decode($departmentSerialized, true));
    }

    /**
     * @return void
     */
    public function testDepartmentSerializerWithGroup()
    {
        $department = new Department();

        $department->setName('SuperDepartment');
        $department->setCode('05');
        $department->setRegion(new Region());

        $departmentSerialized = $this->serializer->serialize($department, 'json', SerializationContext::create()->setGroups(array('location_name', 'department_code')));

        $this->assertEquals(array(
            'name' => 'SuperDepartment',
            'code' => '05',
        ), json_decode($departmentSerialized, true));
    }

    /**
     * @return void
     */
    public function testDepartmentCities()
    {
        $department = new Department();

        $department->setName('SuperDepartment');
        $department->setCode('05');
        $department->setRegion(new Region());

        $city = new City();
        $department->addCity($city);

        $this->assertCount(1, $department->getCities());

        $department->removeCity($city);

        $this->assertCount(0, $department->getCities());
    }

    /**
     * @return array
     */
    public function containsLocationsProvider()
    {
        return [
            ['department-33', 'region-72', false],
            ['department-33', 'region-74', false],
            ['department-33', 'department-33', true],
            ['department-33', 'department-87', false],
            ['department-33', 'city-1', true],
            ['department-33', 'city-2', false],
        ];
    }

    /**
     * @dataProvider containsLocationsProvider
     *
     * @param string  $departmentName
     * @param string  $locationName
     * @param boolean $expected
     *
     * @return void
     */
    public function testContainsLocation($departmentName, $locationName, $expected)
    {
        $department = $this->getReference($departmentName);
        $location = $this->getReference($locationName);

        $this->assertEquals($expected, $department->containsLocation($location));
        $this->assertEquals($expected, $location->isLocatedIn($department));
    }

    /**
     * @return void
     */
    public function testContainsLocationWithNull()
    {
        $location = $this->getReference('department-33');

        $this->assertFalse($location->isLocatedIn(null));
    }
}
