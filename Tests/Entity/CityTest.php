<?php

namespace Chaplean\Bundle\LocationBundle\Tests\Entity;

use Chaplean\Bundle\LocationBundle\Entity\City;
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
class CityTest extends LogicalTest
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
    public function testCity()
    {
        $city = new City();

        $city->setName('SuperCity');
        $city->setZipcode(15000);
        $city->setDepartment(new Department());

        $this->assertEquals('SuperCity', $city->getName());
        $this->assertEquals(15000, $city->getZipcode());
        $this->assertInstanceOf(Department::class, $city->getDepartment());
    }

    /**
     * @return void
     */
    public function testCitySerializerWithoutGroup()
    {
        $city = new City();

        $city->setName('SuperCity');
        $city->setZipcode(15000);
        $city->setDepartment(new Department());

        $citySerialized = $this->serializer->serialize($city, 'json');

        $this->assertEquals(array(
            'name' => 'SuperCity',
            'zipcode' => 15000,
            'department' => array(
                'cities' => array(),
                'dtype' => 'department'
            ),
            'dtype' => 'city',
        ), json_decode($citySerialized, true));
    }

    /**
     * @return void
     */
    public function testCitySerializerWithGroupName()
    {
        $city = new City();

        $city->setName('SuperCity');
        $city->setZipcode(15000);
        $city->setDepartment(new Department());

        $citySerialized = $this->serializer->serialize($city, 'json', SerializationContext::create()->setGroups(array('location_name')));

        $this->assertEquals(array(
            'name' => 'SuperCity'
        ), json_decode($citySerialized, true));
    }

    /**
     * @return void
     */
    public function testCitySerializerWithGroup()
    {
        $city = new City();
        $city->setName('SuperCity');
        $city->setZipcode(15000);
        $city->setDepartment(new Department());

        $citySerialized = $this->serializer->serialize($city, 'json', SerializationContext::create()->setGroups(array('location_name', 'city_zipcode')));

        $this->assertEquals(array(
            'name' => 'SuperCity',
            'zipcode' => 15000,
        ), json_decode($citySerialized, true));
    }

    /**
     * @return void
     */
    public function testGetRegion()
    {
        $region = new Region();
        $region->setName('Region');

        $department = new Department();
        $department->setRegion($region);

        $city = new City();
        $city->setName('SuperCity');
        $city->setZipcode(15000);
        $city->setDepartment($department);

        $this->assertInstanceOf(Region::class, $city->getRegion());
        $this->assertEquals('Region', $city->getRegion()->getName());
    }
}
