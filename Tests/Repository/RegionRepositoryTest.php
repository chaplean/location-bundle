<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\Region;
use Chaplean\Bundle\LocationBundle\Repository\RegionRepository;
use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * RegionRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class RegionRepositoryTest extends LogicalTestCase
{
    /**
     * @var RegionRepository
     */
    protected $regionRepository;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->regionRepository = $this->em->getRepository('ChapleanLocationBundle:Region');
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\RegionRepository::findAll()
     *
     * @return void
     */
    public function testFindAllRegion()
    {
        $regions = $this->regionRepository->findAll();

        $this->assertCount(3, $regions);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\RegionRepository::findOneBy()
     *
     * @return void
     */
    public function testFindRegionByName()
    {
        $region = $this->regionRepository->findOneBy(array('name' => 'Aquitaine'));

        $this->assertInstanceOf(Region::class, $region);
        $this->assertEquals('1', $region->getId());
        $this->assertEquals('Aquitaine', $region->getName());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\RegionRepository::findOneBy()
     *
     * @return void
     */
    public function testFindRegionById()
    {
        $region = $this->regionRepository->findOneBy(array('code' => '74'));

        $this->assertInstanceOf(Region::class, $region);
        $this->assertEquals('74', $region->getCode());
        $this->assertEquals('Limousin', $region->getName());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\RegionRepository::findByZipcode()
     *
     * @return void
     */
    public function testFindRegionByZipcode()
    {
        $region = $this->regionRepository->findByZipcode('18570');

        $this->assertInstanceOf(Region::class, $region);
        $this->assertEquals('24', $region->getCode());
        $this->assertEquals('Centre', $region->getName());

        $region = $this->regionRepository->findByZipcode('33000');

        $this->assertInstanceOf(Region::class, $region);
        $this->assertEquals('Aquitaine', $region->getName());

        $region = $this->regionRepository->findByZipcode('999999999');

        $this->assertNull($region);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\RegionRepository::findOneByCode()
     *
     * @return void
     */
    public function testFindRegionByCode()
    {
        $region = $this->regionRepository->findOneByCode('18');

        $this->assertInstanceOf(Region::class, $region);
        $this->assertEquals('24', $region->getCode());
        $this->assertEquals('Centre', $region->getName());

        $region = $this->regionRepository->findOneByCode('33');

        $this->assertInstanceOf(Region::class, $region);
        $this->assertEquals('Aquitaine', $region->getName());

        $region = $this->regionRepository->findOneByCode('99');

        $this->assertNull($region);
    }
}
