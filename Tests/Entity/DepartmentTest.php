<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Entity;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\LocationBundle\Entity\Region;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

/**
 * DepartmentTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class DepartmentTest extends FunctionalTestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @return void
     * @throws \Exception
     */
    public function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getContainer()->get('jms_serializer');
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::__construct
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::setName()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::setCode()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::setRegion()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::getName()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::getCode()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::getRegion()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::getDepartment()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::getCity()
     *
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

        $this->assertEquals($department, $department->getDepartment());
        $this->assertNull($department->getCity());
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

        $this->assertEquals([
            'name' => 'SuperDepartment',
            'code' => '05',
            'cities' => [],
            'region' => [
                'departments' => [],
                'dtype' => 'region'
            ],
            'dtype' => 'department',
        ], json_decode($departmentSerialized, true));
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

        $departmentSerialized = $this->serializer->serialize($department, 'json', SerializationContext::create()->setGroups(['location_name']));

        $this->assertEquals([
            'name' => 'SuperDepartment'
        ], json_decode($departmentSerialized, true));
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

        $departmentSerialized = $this->serializer->serialize($department, 'json', SerializationContext::create()->setGroups(['location_name', 'department_code']));

        $this->assertEquals([
            'name' => 'SuperDepartment',
            'code' => '05',
        ], json_decode($departmentSerialized, true));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::getCities()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::addCity()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::removeCity()
     *
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
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::containsLocation()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::isLocatedIn()
     *
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
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Department::isLocatedIn()
     *
     * @return void
     */
    public function testContainsLocationWithNull()
    {
        $location = $this->getReference('department-33');

        $this->assertFalse($location->isLocatedIn(null));
    }
}
