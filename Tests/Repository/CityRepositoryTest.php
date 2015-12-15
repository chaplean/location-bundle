<?php

namespace Chaplean\Bundle\LocationBundle\Tests\Repository;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\UnitBundle\Test\LogicalTest;
use Doctrine\ORM\EntityRepository;

/**
 * RegionRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class CityRepositoryTest extends LogicalTest
{
    /** @var  EntityRepository */
    protected $cityRepository;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::loadStaticFixtures(
            array(
                'Chaplean\Bundle\LocationBundle\DataFixtures\Liip\LoadCityData',
                'Chaplean\Bundle\LocationBundle\DataFixtures\Liip\LoadDepartmentData',
                'Chaplean\Bundle\LocationBundle\DataFixtures\Liip\LoadRegionData'
            )
        );
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $this->cityRepository = $this->em->getRepository('ChapleanLocationBundle:City');
    }

    /**
     * @return void
     */
    public function testFindAllCity()
    {
        $cities = $this->cityRepository->findAll();

        $this->assertCount(6, $cities);
    }

    /**
     * @return void
     */
    public function testFindOneCityByName()
    {
        $city = $this->cityRepository->findOneBy(array('name' => 'Le Subdray'));

        $this->assertTrue($city instanceof City);
        $this->assertEquals('6', $city->getDepartment()->getId());
        $this->assertEquals('18570', $city->getZipcode());
    }

    /**
     * @return void
     */
    public function testFindOneCityByZipcode()
    {
        $city = $this->cityRepository->findOneBy(array('zipcode' => '87000'));

        $this->assertTrue($city instanceof City);
        $this->assertEquals('5', $city->getDepartment()->getId());
        $this->assertEquals('Limoges', $city->getName());
    }
}