<?php

namespace Chaplean\Bundle\LocationBundle\Repository;

use Chaplean\Bundle\LocationBundle\Entity\Region;
use Doctrine\ORM\EntityRepository;

/**
 * Class RegionRepository.
 *
 * @package   Chaplean\Bundle\LocationBundle\Repository
 * @author    Benoit - Chaplean <benoit@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     3.0.2
 */
class RegionRepository extends EntityRepository
{
    const DOM_TOM_REGION_CODES = ['01', '02', '03', '04', '06', '99'];
    /**
     * @param string $zipcode
     *
     * @return Region
     * @throws \Exception
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

    /**
     * @param string $code
     *
     * @return Region
     * @throws \Exception
     */
    public function findOneByCode($code)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->join('r.departments', 'd')
            ->where(
                $qb->expr()->eq('d.code', $qb->expr()->literal($code))
            );

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array|Region[]
     * @throws \Exception
     */
    public function findAllMetropolitan()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->where(
                $qb->expr()->notIn('r.code', self::DOM_TOM_REGION_CODES)
            );

        return $qb->getQuery()->getResult();
    }
}
