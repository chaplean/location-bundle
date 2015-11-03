<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRegionData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class LoadDepartmentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $fileDepartment = new CsvReader(__DIR__ . '/../../Resources/doc/departments_2014.csv');
        $departments = $fileDepartment->extractData("\t", 1);
        foreach ($departments as $departmentTxt) {
            $department = new Department();
            $department->setCode($departmentTxt[1]);
            $department->setName(ucwords($departmentTxt[5]));
            $department->setRegion($this->getReference('region-' . $departmentTxt[0]));
            $manager->persist($department);
            $this->setReference('department-' . $departmentTxt[1], $department);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
