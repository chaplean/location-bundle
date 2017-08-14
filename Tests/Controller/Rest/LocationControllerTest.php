<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Controller\Rest;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * LocationControllerTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     3.0.0
 */
class LocationControllerTest extends LogicalTestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Controller\Rest\LocationController::getLocationAction()
     *
     * @return void
     */
    public function testSearchLocation()
    {
        $client = $this->createRestClient();

        $client->request('GET', '/rest/location/search/{location}', array(
            'location' => 'haute-v'
        ));

        $response = $client->getContent();

        $this->assertCount(1, $response['results']);
        $this->assertEquals($response['results'][0]['name'], 'Haute-Vienne');
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Controller\Rest\LocationController::getLocationAction()
     *
     * @return void
     */
    public function testSearchLocationMoreResult()
    {
        $client = $this->createRestClient();

        $client->request('GET', '/rest/location/search/{location}', array(
            'location' => 'l'
        ));

        $response = $client->getContent();

        $this->assertCount(5, $response['results']);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Controller\Rest\LocationController::getLocationAction()
     *
     * @return void
     */
    public function testSearchLocationZipcode()
    {
        $client = $this->createRestClient();

        $client->request('GET', '/rest/location/search/{location}', array(
            'location' => '18'
        ));

        $response = $client->getContent();

        $this->assertCount(3, $response['results']);
    }
}
