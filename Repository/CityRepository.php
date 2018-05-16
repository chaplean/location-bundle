<?php

namespace Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Doctrine\ORM\EntityRepository;

/**
 * Class CityRepository.
 *
 * @package   Chaplean\Bundle\LocationBundle\Repository
 * @author    Benoit - Chaplean <benoit@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     3.0.2
 *
 * @method City|null findOneByCode(string $code)
 */
class CityRepository extends EntityRepository
{
    /**
     * @param int $search
     *
     * @return array
     */
    public function findZipcodeFromSearch($search)
    {
        $qb = $this->_em->createQueryBuilder();

        $result = $qb->select('city.zipcode')
            ->from('ChapleanLocationBundle:City', 'city')
            ->where('city.zipcode LIKE :search')
            ->setParameter('search', $search . '%')
            ->orderBy('city.zipcode', 'ASC')
            ->getQuery()
            ->getResult();

        if (!empty($result)) {
            $zipcodes = array_column($result, 'zipcode');
            return array_unique($zipcodes);
        }

        return [];
    }
}
