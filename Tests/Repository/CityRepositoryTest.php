<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Repository\CityRepository;
use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * RegionRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class CityRepositoryTest extends LogicalTestCase
{
    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->cityRepository = $this->em->getRepository('ChapleanLocationBundle:City');
    }

    /**
     * @return void
     */
    public function testFindAllCity()
    {
        $cities = $this->cityRepository->findAll();

        $this->assertCount(8, $cities);
    }

    /**
     * @return void
     */
    public function testFindOneCityByName()
    {
        $city = $this->cityRepository->findOneBy(array('name' => 'Le Subdray'));

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals(6, $city->getDepartment()->getId());
        $this->assertEquals('18570', $city->getZipcode());
    }

    /**
     * @return void
     */
    public function testFindOneCityByZipcode()
    {
        $city = $this->cityRepository->findOneBy(array('zipcode' => '87000'));

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals(5, $city->getDepartment()->getId());
        $this->assertEquals('Limoges', $city->getName());
    }

    /**
     * @return void
     */
    public function testFindOneCityByNameAndCoordinates()
    {
        $city = $this->cityRepository->findOneByNameAndCoordinates('Bourges', 47.0833, 2.4);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('Bourges', $city->getName());
        $this->assertEquals(47.0833, $city->getLatitude());
        $this->assertEquals(2.4, $city->getLongitude());
    }

    /**
     * @return void
     */
    public function testFindOneCityByNameAndCoordinatesUnknownName()
    {
        $city = $this->cityRepository->findOneByNameAndCoordinates('Test', 47.0833, 2.4);

        $this->assertNull($city);
    }

    /**
     * @return void
     */
    public function testFindOneCityByNameAndCoordinatesWithSameName()
    {
        $city = $this->cityRepository->findOneByNameAndCoordinates('Bordeaux', 44.83, -0.57);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('Bordeaux', $city->getName());
        $this->assertEquals(44.8333, $city->getLatitude());
        $this->assertEquals(-0.566667, $city->getLongitude());
    }

    /**
     * @return void
     */
    public function testFindOneCityByNameAndCoordinatesWithSameCoordinates()
    {
        $city = $this->cityRepository->findOneByNameAndCoordinates('Fausse-Ville', 44.83, -0.57);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('Fausse-Ville', $city->getName());
        $this->assertEquals(44.8333, $city->getLatitude());
        $this->assertEquals(-0.566667, $city->getLongitude());
    }
}
