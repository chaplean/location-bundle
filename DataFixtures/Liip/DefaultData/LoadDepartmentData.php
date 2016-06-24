<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\Liip\DefaultData;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadDepartmentData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class LoadDepartmentData extends AbstractFixture implements DependentFixtureInterface
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
        $fileDepartment = new CsvReader(__DIR__ . '/../../../Tests/Resources/doc/departments_test.csv');
        $departments = $fileDepartment->extractData(';');

        foreach ($departments as $departmentTxt) {
            $department = new Department();
            $department->setCode($departmentTxt[1]);
            $department->setName(ucwords($departmentTxt[2]));
            $department->setRegion($this->getReference('region-' . $departmentTxt[0]));

            $manager->persist($department);

            $this->addReference('department-' . $departmentTxt[1], $department);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            'Chaplean\Bundle\LocationBundle\DataFixtures\Liip\DefaultData\LoadRegionData'
        );
    }
}
