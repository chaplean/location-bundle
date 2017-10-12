<?php

namespace Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Repository\CityRepository;
use Doctrine\ORM\EntityManager;
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
     * @var EntityManager
     */
    private $em;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * LocationUpgradeCitiesUtility constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->cityRepository = $this->em->getRepository('ChapleanLocationBundle:City');
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
