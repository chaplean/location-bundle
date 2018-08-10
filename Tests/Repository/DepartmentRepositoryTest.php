<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\Department;
use Chaplean\Bundle\LocationBundle\Repository\DepartmentRepository;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;

/**
 * DepartmentRepositoryTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class DepartmentRepositoryTest extends FunctionalTestCase
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
     * @covers \Chaplean\Bundle\LocationBundle\Repository\DepartmentRepository::findAll()
     *
     * @return void
     */
    public function testFindAllDepartment()
    {
        $departments = $this->departmentRepository->findAll();

        $this->assertCount(7, $departments);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\DepartmentRepository::findOneBy()
     *
     * @return void
     */
    public function testFindOneDepartmentById()
    {
        $department = $this->departmentRepository->findOneBy(['id' => '6']);

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals('Gironde', $department->getName());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\DepartmentRepository::findOneBy()
     *
     * @return void
     */
    public function testFindOneDepartmentByName()
    {
        $department = $this->departmentRepository->findOneBy(['name' => 'Cher']);

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals('3', $department->getRegion()->getId());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Repository\DepartmentRepository::findOneBy()
     *
     * @return void
     */
    public function testFindOneDepartmentByRegion()
    {
        $regionRepository = $this->em->getRepository('ChapleanLocationBundle:Region');
        $region = $regionRepository->findOneBy(['name' => 'Aquitaine']);
        $department = $this->departmentRepository->findOneBy(['region' => $region]);

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals('Gironde', $department->getName());
    }
}
