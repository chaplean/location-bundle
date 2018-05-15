<?php

namespace Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\Department;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class DepartmentUtility.
 *
 * @package   Chaplean\Bundle\LocationBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     10.0.0
 */
class DepartmentUtility
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * DepartmentUtility constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    public function loadDepartment()
    {
        $regionRepository = $this->em->getRepository('ChapleanLocationBundle:Region');

        $fileDepartment = new CsvReader(__DIR__ . '/../../Resources/doc/departments_2014.csv', "\t", true);
        $fileDepartmentCom = new CsvReader(__DIR__ . '/../../Resources/doc/com_departments_2016.csv', "\t", true);

        $departments = array_merge($fileDepartment->get(), $fileDepartmentCom->get());

        foreach ($departments as $departmentTxt) {
            $regionCode = RegionUtility::getNewCodeRegion($departmentTxt[0]);
            $region = $regionRepository->findOneByCode($regionCode);

            if ($region === null) {
                continue;
            }

            $department = new Department();
            $department->setCode($departmentTxt[1]);
            $department->setName(ucwords($departmentTxt[5]));
            $department->setRegion($region);

            $this->em->persist($department);
        }

        $this->em->flush();
    }
}
