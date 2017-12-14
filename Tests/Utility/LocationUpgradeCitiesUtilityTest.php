<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class LocationUpgradeCitiesUtilityTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class LocationUpgradeCitiesUtilityTest extends MockeryTestCase
{
    /** @var \Mockery\MockInterface|RegistryInterface */
    private $doctrine;
    /** @var \Mockery\MockInterface */
    private $em;
    /** @var \Mockery\MockInterface */
    private $repository;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->doctrine = \Mockery::mock(RegistryInterface::class);
        $this->em = \Mockery::mock(EntityManager::class);
        $this->repository = \Mockery::mock(EntityRepository::class);

        $this->doctrine->shouldReceive('getManager')->once()->andReturn($this->em);
        $this->em->shouldReceive('getRepository')->twice()->andReturn($this->repository);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::__construct
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::isCityExisting
     *
     * @return void
     */
    public function testIsCityExisting()
    {
        $this->repository->shouldReceive('findOneBy')->once()->with(['name' => 'foo', 'zipcode' => 'bar'])->andReturnNull();

        $locationUpgradeCitiesUtility = new LocationUpgradeCitiesUtility($this->doctrine);

        $this->assertFalse($locationUpgradeCitiesUtility->isCityExisting('foo', 'bar'));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::__construct
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::getNewCities
     *
     * @return void
     */
    public function testGetNewCities()
    {
        $locationUpgradeCitiesUtility = new LocationUpgradeCitiesUtility($this->doctrine);

        $this->assertCount(39201, $locationUpgradeCitiesUtility->getNewCities());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::createCityFromCsvRow()
     *
     * @return void
     */
    public function testCreateCityFromRow()
    {
        $locationUpgradeCitiesUtility = new LocationUpgradeCitiesUtility($this->doctrine);

        $this->repository->shouldReceive('findOneByCode')->once()->andReturn(new Department());

        $city = $locationUpgradeCitiesUtility->createCityFromCsvRow([
            '00000',
            'Bordeaux',
            '33000',
            '',
            '',
            '5,-3'
        ]);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('00000', $city->getCodeInsee());
        $this->assertEquals('Bordeaux', $city->getName());
        $this->assertEquals('33000', $city->getZipcodeString());
        $this->assertEquals(5, $city->getLatitude());
        $this->assertEquals(-3, $city->getLongitude());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility::createCityFromCsvRow()
     *
     * @return void
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     * @expectedExceptionMessage Department (33) not found
     */
    public function testCreateCityFromRowNotFoundDepartment()
    {
        $locationUpgradeCitiesUtility = new LocationUpgradeCitiesUtility($this->doctrine);

        $this->repository->shouldReceive('findOneByCode')->once()->andReturnNull();

        $locationUpgradeCitiesUtility->createCityFromCsvRow([
            '00000',
            'Bordeaux',
            '33000',
            '',
            '',
            '5,-3'
        ]);
    }
}
