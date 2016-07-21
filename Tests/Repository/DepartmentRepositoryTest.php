<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\LocationBundle\Repository\DepartmentRepository;
use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * RegionRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class DepartmentRepositoryTest extends LogicalTestCase
{
    /**
     * @var DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

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

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals('Haute-Vienne', $department->getName());
        $this->assertEquals('2', $department->getRegion()->getId());
    }

    /**
     * @return void
     */
    public function testFindOneDepartmentByName()
    {
        $department = $this->departmentRepository->findOneBy(array('name' => 'Cher'));

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals('6', $department->getId());
        $this->assertEquals('3', $department->getRegion()->getId());
    }

    /**
     * @return void
     */
    public function testFindOneDepartmentByRegion()
    {
        $regionRepository = $this->em->getRepository('ChapleanLocationBundle:Region');
        $region = $regionRepository->findOneBy(array('name' => 'Aquitaine'));
        $department = $this->departmentRepository->findOneBy(array('region' => $region));

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals('4', $department->getId());
        $this->assertEquals('Gironde', $department->getName());
    }
}
