<?php

namespace Chaplean\Bundle\LocationBundle\Tests\Repository;

use Chaplean\Bundle\LocationBundle\Entity\Region;
use Chaplean\Bundle\UnitBundle\Test\LogicalTest;
use Doctrine\ORM\EntityRepository;

/**
 * RegionRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class RegionRepositoryTest extends LogicalTest
{
    /** @var  EntityRepository */
    protected $regionRepository;

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
        $this->regionRepository = $this->em->getRepository('ChapleanLocationBundle:Region');
    }

    /**
     * @return void
     */
    public function testFindAllRegion()
    {
        $regions = $this->regionRepository->findAll();

        $this->assertCount(3, $regions);
    }

    /**
     * @return void
     */
    public function testFindRegionByName()
    {
        $region = $this->regionRepository->findOneBy(array('name' => 'Aquitaine'));

        $this->assertTrue($region instanceof Region);

        $this->assertEquals('1', $region->getId());
        $this->assertEquals('Aquitaine', $region->getName());
    }

    /**
     * @return void
     */
    public function testFindRegionById()
    {
        $region = $this->regionRepository->findOneBy(array('code' => '74'));

        $this->assertTrue($region instanceof Region);
        $this->assertEquals('74', $region->getCode());
        $this->assertEquals('Limousin', $region->getName());
    }
}