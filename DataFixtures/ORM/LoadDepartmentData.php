<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRegionData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
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
        $fileDepartment = new CsvReader(__DIR__ . '/../../Resources/doc/departments_2014.csv', "\t", true);
        $fileDepartmentCom = new CsvReader(__DIR__ . '/../../Resources/doc/com_departments_2016.csv', "\t", true);

        $departments = array_merge($fileDepartment->get(), $fileDepartmentCom->get());

        foreach ($departments as $departmentTxt) {
            $region = LoadRegionData::getNewCodeRegion($departmentTxt[0]);

            $department = new Department();
            $department->setCode($departmentTxt[1]);
            $department->setName(ucwords($departmentTxt[5]));
            $department->setRegion($this->getReference($region));

            $manager->persist($department);
            $this->setReference('department-' . $departmentTxt[1], $department);
        }

        $manager->flush();
    }

    /**
     * @codeCoverageIgnore
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            'Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadRegionData',
        );
    }
}
