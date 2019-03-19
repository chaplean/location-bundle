<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Entity;

use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\LocationBundle\Entity\Region;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Symfony\Component\Serializer\Serializer;

/**
 * RegionCity.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RegionTest extends FunctionalTestCase
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

        $this->serializer = $this->getContainer()
            ->get('serializer');
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::__construct()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::setName()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::setCode()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::getName()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::getCode()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::getRegion()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::getDepartment()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::getCity()
     *
     * @return void
     */
    public function testRegion()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $this->assertEquals('SuperRegion', $region->getName());
        $this->assertEquals('05', $region->getCode());

        $this->assertEquals($region, $region->getRegion());
        $this->assertNull($region->getDepartment());
        $this->assertNull($region->getCity());
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

        $this->assertEquals(
            [
                'name'        => 'SuperRegion',
                'code'        => '05',
                'departments' => [],
                'dtype'       => 'region',
            ],
            json_decode($regionSerialized, true)
        );
    }

    /**
     * @return void
     */
    public function testRegionSerializerWithGroupName()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $regionSerialized = $this->serializer->serialize(
            $region,
            'json',
            ['groups' => ['location_name']]
        );

        $this->assertEquals(
            [
                'name' => 'SuperRegion'
            ],
            json_decode($regionSerialized, true)
        );
    }

    /**
     * @return void
     */
    public function testRegionSerializerWithGroup()
    {
        $region = new Region();

        $region->setName('SuperRegion');
        $region->setCode('05');

        $regionSerialized = $this->serializer->serialize(
            $region,
            'json',
            ['groups' => ['location_name', 'region_code']]
        );

        $this->assertEquals(
            [
                'name' => 'SuperRegion',
                'code' => '05',
            ],
            json_decode($regionSerialized, true)
        );
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::getDepartments()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::addDepartment()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::removeDepartment()
     *
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

    /**
     * @return array
     */
    public function containsLocationsProvider()
    {
        return [
            ['region-72', 'region-72', true],
            ['region-72', 'region-74', false],
            ['region-72', 'department-33', true],
            ['region-72', 'department-87', false],
            ['region-72', 'city-1', true],
            ['region-72', 'city-2', false],
        ];
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::containsLocation()
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::isLocatedIn()
     *
     * @dataProvider containsLocationsProvider
     *
     * @param string  $regionName
     * @param string  $locationName
     * @param boolean $expected
     *
     * @return void
     */
    public function testContainsLocation($regionName, $locationName, $expected)
    {
        $region = $this->getReference($regionName);
        $location = $this->getReference($locationName);

        $this->assertEquals($expected, $region->containsLocation($location));
        $this->assertEquals($expected, $location->isLocatedIn($region));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Entity\Region::isLocatedIn()
     *
     * @return void
     */
    public function testContainsLocationWithNull()
    {
        $location = $this->getReference('region-72');

        $this->assertFalse($location->isLocatedIn(null));
    }
}
