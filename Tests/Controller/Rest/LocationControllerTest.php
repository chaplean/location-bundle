<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Controller\Rest;

use Chaplean\Bundle\UnitBundle\Test\LogicalTest;

/**
 * LocationControllerTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     3.0.0
 */
class LocationControllerTest extends LogicalTest
{
    /**
     * @return void
     */
    public function testSearchLocation()
    {
        $client = $this->createRestClient();

        $client->requestGet('/rest/location/search/{location}', array(
            'location' => 'haute-v'
        ));

        $response = $client->getContent();

        $this->assertCount(1, $response['results']);
        $this->assertEquals($response['results'][0]['name'], 'Haute-Vienne');
    }

    /**
     * @return void
     */
    public function testSearchLocationMoreResult()
    {
        $client = $this->createRestClient();

        $client->requestGet('/rest/location/search/{location}', array(
            'location' => 'l'
        ));

        $response = $client->getContent();

        $this->assertCount(5, $response['results']);
    }

    /**
     * @return void
     */
    public function testSearchLocationZipcode()
    {
        $client = $this->createRestClient();

        $client->requestGet('/rest/location/search/{location}', array(
            'location' => '18'
        ));

        $response = $client->getContent();

        $this->assertCount(3, $response['results']);
    }
}
