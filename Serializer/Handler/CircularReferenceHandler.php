<?php

namespace Chaplean\Bundle\LocationBundle\Serializer\Handler;

use Chaplean\Bundle\LocationBundle\Entity\Department;

/**
 * Class CircularReferenceHandler.
 *
 * @package   Chaplean\Bundle\LocationBundle\Serializer\Handler
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2019 Chaplean (http://www.chaplean.coop)
 */
class CircularReferenceHandler
{
    public function __invoke()
    {
        [$object] = func_get_args();

        if ($object instanceof Department) {
            return ['id' => $object->getId()];
        }
    }
}
