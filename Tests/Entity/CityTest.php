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
class CityTest extends LogicalTestCase
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
    public function testCity()
    {
        $city = new City();

        $city->setName('SuperCity');
        $city->setZipcode(15000);
        $city->setLatitude(44.9167);
        $city->setLongitude(2.45);
        $city->setDepartment(new Department());

        $this->assertEquals('SuperCity', $city->getName());
        $this->assertEquals(15000, $city->getZipcode());
        $this->assertEquals(44.9167, $city->getLatitude());
        $this->assertEquals(2.45, $city->getLongitude());
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
        $city->setLatitude(44.9167);
        $city->setLongitude(2.45);
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
        $city->setLatitude(44.9167);
        $city->setLongitude(2.45);
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
        $city->setLatitude(44.9167);
        $city->setLongitude(2.45);
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
        $city->setLatitude(44.9167);
        $city->setLongitude(2.45);
        $city->setDepartment($department);

        $this->assertInstanceOf(Region::class, $city->getRegion());
        $this->assertEquals('Region', $city->getRegion()->getName());
    }
}
