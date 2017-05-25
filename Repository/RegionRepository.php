<?php

namespace Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\Region;
use Doctrine\ORM\EntityRepository;

/**
 * Class RegionRepository.
 *
 * @package   Chaplean\Bundle\LocationBundle\Repository
 * @author    Benoit - Chaplean <benoit@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     3.0.2
 */
class RegionRepository extends EntityRepository
{
    /**
     * @param string $zipcode
     *
     * @return Region
     */
    public function findByZipcode($zipcode)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->join('r.departments', 'd')
            ->join('d.cities', 'c')
            ->where($qb->expr()->eq('c.zipcode', $qb->expr()->literal($zipcode)))
            ->groupBy('r.id');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
