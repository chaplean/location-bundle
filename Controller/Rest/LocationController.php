<?php

namespace Chaplean\Bundle\LocationBundle\Controller\Rest;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Response;

/**
 * LocationController.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     3.0.0
 *
 * @Annotations\RouteResource("Location")
 */
class LocationController extends FOSRestController
{
    /**
     * @Annotations\Get("/location/search/{location}")
     *
     * @param string $location
     *
     * @return Response
     */
    public function getLocationAction($location)
    {
        $cities      = new ArrayCollection($this->getDoctrine()->getRepository('ChapleanLocationBundle:City')->findAll());
        $departments = new ArrayCollection($this->getDoctrine()->getRepository('ChapleanLocationBundle:Department')->findAll());
        $regions     = new ArrayCollection($this->getDoctrine()->getRepository('ChapleanLocationBundle:Region')->findAll());

        $location = ucwords(str_replace('_', ' ', str_replace(' ', '-', ucwords(str_replace('-', ' ', str_replace(' ', '_', $location))))));

        $criteria = Criteria::create();

        $citiesMatch = $cities->matching($criteria->where(Criteria::expr()->contains('name', $location))->orWhere(Criteria::expr()->contains('zipcode', $location)));
        $departmentsMatch = $departments->matching($criteria->where(Criteria::expr()->contains('name', $location)));
        $regionsMatch = $regions->matching($criteria->where(Criteria::expr()->contains('name', $location)));

        $view = $this->view(array('results' => array_merge($citiesMatch->toArray(), $departmentsMatch->toArray(), $regionsMatch->toArray())));
        $context = new Context();
        $context->setGroups(array(
            'location_id', 'location_name', 'city_zipcode'
        ));
        $view->setContext($context);

        return $this->handleView($view);
    }
}
