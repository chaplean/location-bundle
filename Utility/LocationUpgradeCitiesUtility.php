<?php

namespace Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Repository\CityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class LocationUpgradeCitiesUtility.
 *
 * @package   Chaplean\Bundle\LocationBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class LocationUpgradeCitiesUtility
{
    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var CityRepository
     */
    private $departmentRepository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * LocationUpgradeCitiesUtility constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->cityRepository = $this->em->getRepository('ChapleanLocationBundle:City');
        $this->departmentRepository = $this->em->getRepository('ChapleanLocationBundle:Department');
    }

    /**
     * @param $lineCsv
     *
     * @return \Chaplean\Bundle\LocationBundle\Entity\City
     * @throws \Exception
     */
    public function createCityFromCsvRow($lineCsv)
    {
        $name = CityUtility::removeNumber($lineCsv[1]);
        $zipcode = $lineCsv[2];

        $code = CityUtility::getDepartmentCodeFromZipcode($zipcode);
        $coords = CityUtility::extractLatitudeLongitude($lineCsv[5]);
        $department = $this->departmentRepository->findOneByCode($code);

        if ($department === null) {
            throw new EntityNotFoundException('Department (' . $code . ') not found');
        }

        $city = new City();
        $city->setCodeInsee($lineCsv[0]);
        $city->setName($name);
        $city->setZipcode($zipcode);
        $city->setLatitude($coords['latitude']);
        $city->setLongitude($coords['longitude']);
        $city->setDepartment($department);

        return $city;
    }

    /**
     * @param string $name
     * @param string $zipcode
     *
     * @return boolean
     */
    public function isCityExisting($name, $zipcode)
    {
        return $this->cityRepository->findOneBy(['name' => $name, 'zipcode' => $zipcode]) !== null;
    }

    /**
     * @return array
     */
    public function getNewCities()
    {
        $fileCity = new CsvReader(__DIR__ . '/../Resources/doc/cities_2017.csv', ';', true);

        return $fileCity->get();
    }
}
