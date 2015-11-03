<?php

namespace Chaplean\Bundle\LocationBundle\Tests\Repository;

use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\UnitBundle\Test\LogicalTest;
use Doctrine\ORM\EntityRepository;

/**
 * RegionRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.17.0
 */
class DepartmentRepositoryTest extends LogicalTest
{
    /** @var  EntityRepository */
    protected $departmentRepository;

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
        $this->departmentRepository = $this->em->getRepository('ChapleanLocationBundle:Department');
    }

    /**
     * @return void
     */
    public function testFindAllDepartment()
    {
        $departments = $this->departmentRepository->findAll();

        $this->assertCount(5, $departments);
    }

    /**
     * @return void
     */
    public function testFindOneDepartmentById()
    {
        $department = $this->departmentRepository->findOneBy(array('id' => '5'));

        $this->assertTrue($department instanceof Department);
        $this->assertEquals('Haute-Vienne', $department->getName());
        $this->assertEquals(
            '2', $department->getRegion()
            ->getId()
        );
    }

    /**
     * @return void
     */
    public function testFindOneDepartmentByName()
    {
        $department = $this->departmentRepository->findOneBy(array('name' => 'Cher'));

        $this->assertTrue($department instanceof Department);
        $this->assertEquals('6', $department->getId());
        $this->assertEquals(
            '3', $department->getRegion()
            ->getId()
        );
    }

    /**
     * @return void
     */
    public function testFindOneDepartmentByRegion()
    {
        $regionRepository = $this->em->getRepository('ChapleanLocationBundle:Region');
        $region = $regionRepository->findOneBy(array('name' => 'Aquitaine'));
        $department = $this->departmentRepository->findOneBy(array('region' => $region));

        $this->assertTrue($department instanceof Department);
        $this->assertEquals('4', $department->getId());
        $this->assertEquals('Gironde', $department->getName());
    }
}
