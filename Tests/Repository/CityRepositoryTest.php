<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Repository\CityRepository;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;

/**
 * CityRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class CityRepositoryTest extends FunctionalTestCase
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
     * @covers \Chaplean\Bundle\LocationBundle\Repository\CityRepository::findAll()
     *
     * @return void
     */
    public function testFindAllCity()
    {
        $cities = $this->cityRepository->findAll();

        $this->assertCount(11, $cities);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\CityRepository::findOneBy()
     *
     * @return void
     */
    public function testFindOneCityByName()
    {
        $city = $this->cityRepository->findOneBy(['name' => 'Le Subdray']);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('18570', $city->getZipcode());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\CityRepository::findOneBy()
     *
     * @return void
     */
    public function testFindOneCityByZipcode()
    {
        $city = $this->cityRepository->findOneBy(['zipcode' => '87000']);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('Limoges', $city->getName());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\CityRepository::findZipcodeFromSearch()
     *
     * @return void
     */
    public function testFindZipcodeFromSearch()
    {
        $results = $this->cityRepository->findZipcodeFromSearch(87);

        $this->assertCount(1, $results);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\CityRepository::findMetropolitanZipcodeFromSearch()
     *
     * @return void
     */
    public function testFindMetropolitanZipcodeFromSearch()
    {
        $results = $this->cityRepository->findMetropolitanZipcodeFromSearch(97);

        $this->assertCount(0, $results);
    }
}
