<?php

namespace Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Doctrine\ORM\EntityRepository;

/**
 * Class CityRepository.
 *
 * @package   Chaplean\Bundle\LocationBundle\Repository
 * @author    Benoit - Chaplean <benoit@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     3.0.2
 */
class CityRepository extends EntityRepository
{
    /**
     * Return a city according to the given name and coordinates.
     *
     * @param string $name      City's name
     * @param float  $latitude  City's latitude
     * @param float  $longitude City's longitude
     *
     * @return City
     */
    public function findOneByNameAndCoordinates($name, $latitude, $longitude)
    {
        $city = null;
        $distance = null;
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c')
            ->from('ChapleanLocationBundle:City', 'c')
            ->where($qb->expr()->eq('c.name', ':name'))
            ->setParameter('name', $name)
            ->setMaxResults(20);

        $results = $qb->getQuery()->getResult();

        /** @var City $result */
        foreach ($results as $result) {
            $cityLatitude = $result->getLatitude();
            $cityLongitude = $result->getLongitude();

            $cityDistance = 3959 * acos(cos(deg2rad($latitude)) * cos(deg2rad($cityLatitude)) * cos(deg2rad($cityLongitude) - deg2rad($longitude)) + sin(deg2rad($latitude)) * sin(deg2rad($cityLatitude)));

            if ($distance === null || $cityDistance < $distance) {
                $city = $result;
                $distance = $cityDistance;
            }
        }

        return $city;
    }
}
