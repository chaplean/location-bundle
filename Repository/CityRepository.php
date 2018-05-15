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
}
