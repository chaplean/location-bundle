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
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
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

    /**
     * @return void
     */
    public function testGetZipcodeString()
    {
        $city1 = new City();
        $city1->setZipcode(33000);

        $city2 = new City();
        $city2->setZipcode(9000);

        $this->assertEquals('33000', $city1->getZipcodeString());
        $this->assertTrue('09000' === $city2->getZipcodeString()); // because assertEquals (is stupid) behaves like == and thinks '9000' == '09000'
    }

    /**
     * @return array
     */
    public function containsLocationsProvider()
    {
        return [
            ['city-1', 'region-72', false],
            ['city-1', 'region-74', false],
            ['city-1', 'department-33', false],
            ['city-1', 'department-87', false],
            ['city-1', 'city-1', true],
            ['city-1', 'city-2', false],
        ];
    }

    /**
     * @dataProvider containsLocationsProvider
     *
     * @param string  $cityName
     * @param string  $locationName
     * @param boolean $expected
     *
     * @return void
     */
    public function testContainsLocation($cityName, $locationName, $expected)
    {
        $city = $this->getReference($cityName);
        $location = $this->getReference($locationName);

        $this->assertEquals($expected, $city->containsLocation($location));
        $this->assertEquals($expected, $location->isLocatedIn($city));
    }

    /**
     * @return void
     */
    public function testContainsLocationWithNull()
    {
        $location = $this->getReference('city-1');

        $this->assertFalse($location->isLocatedIn(null));
    }
}
