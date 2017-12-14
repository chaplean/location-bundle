<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Utility\CityUtility;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;

/**
 * Class CityUtility.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class CityUtilityTest extends FunctionalTestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::reformatName()
     *
     * @return void
     */
    public function testReformatName()
    {
        $this->assertEquals('LE SUBDRAY', CityUtility::reformatName('Le Subdray'));
        $this->assertEquals('ST ANDRE', CityUtility::reformatName('Saint-André'));
        $this->assertEquals('STE EUPHEMIE', CityUtility::reformatName('Sainte-Euphémie'));
        $this->assertEquals('TRINITE', CityUtility::reformatName('Trinité'));
        $this->assertEquals('MORNE A L EAU', CityUtility::reformatName('Morne-à-l\'Eau'));
        $this->assertEquals('SANTA LUCIA DI MORIANI', CityUtility::reformatName('Santa-Lucia-di-Moriani'));
        $this->assertEquals('COURSAINT', CityUtility::reformatName('Coursaint'));
        $this->assertEquals('SAINTENY', CityUtility::reformatName('Sainteny'));
        $this->assertEquals('LA CHAPELLE ST URSIN', CityUtility::reformatName('La Chapelle-Saint-Ursin'));
        $this->assertEquals('LE PAVILLON STE JULIE', CityUtility::reformatName('Le Pavillon-Sainte-Julie'));
        $this->assertEquals('NIEUL LES SAINTES', CityUtility::reformatName('Nieul-lès-Saintes'));
        $this->assertEquals('', CityUtility::reformatName(null));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::getDepartmentCodeFromZipcode()
     *
     * @return void
     */
    public function testGetDepartmentCodeFromZipcode()
    {
        $this->assertEquals('18', CityUtility::getDepartmentCodeFromZipcode('18562'));
        $this->assertEquals('999', CityUtility::getDepartmentCodeFromZipcode('99999'));
        $this->assertEquals('1', CityUtility::getDepartmentCodeFromZipcode('1'));
        $this->assertEquals('97', CityUtility::getDepartmentCodeFromZipcode('97'));
        $this->assertEquals('', CityUtility::getDepartmentCodeFromZipcode(''));
        $this->assertEquals('2A', CityUtility::getDepartmentCodeFromZipcode('20'));
        $this->assertEquals('2B', CityUtility::getDepartmentCodeFromZipcode('21'));
        $this->assertEquals('', CityUtility::getDepartmentCodeFromZipcode(null));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::extractLatitudeLongitude()
     *
     * @return void
     */
    public function testExtractLongitudeLatitude()
    {
        $this->assertEquals(['longitude' => 6.1760319489, 'latitude' => 48.6902043213], CityUtility::extractLatitudeLongitude('48.6902043213, 6.1760319489'));

        $coords = CityUtility::extractLatitudeLongitude('');
        $this->assertSame(0, $coords['longitude']);
        $this->assertSame(0, $coords['latitude']);

        $coords = CityUtility::extractLatitudeLongitude('a,a');
        $this->assertSame(0, $coords['longitude']);
        $this->assertSame(0, $coords['latitude']);

        $coords = CityUtility::extractLatitudeLongitude(null);
        $this->assertSame(0, $coords['longitude']);
        $this->assertSame(0, $coords['latitude']);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::removeNumber()
     *
     * @return void
     */
    public function testremoveNumber()
    {
        $this->assertEquals('PARIS', CityUtility::removeNumber('PARIS 01'));
        $this->assertEquals('PARIS', CityUtility::removeNumber('PARIS 02'));
        $this->assertEquals('PARIS', CityUtility::removeNumber('PARIS 2121'));
        $this->assertEquals('Paris', CityUtility::removeNumber('01 Paris'));
        $this->assertEquals('Foos', CityUtility::removeNumber('Foos'));
        $this->assertEquals('PoSq', CityUtility::removeNumber('Po01Sq'));
        $this->assertEquals('', CityUtility::removeNumber(null));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::findNearestCityByNameAndCoordinates()
     *
     * @return void
     */
    public function testFindOneCityByNameAndCoordinates()
    {
        $cityUtility = $this->getContainer()->get('chaplean_location.city_utility');
        $city = $cityUtility->findNearestCityByNameAndCoordinates('Bourges', 47.0833, 2.4);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('Bourges', $city->getName());
        $this->assertEquals(47.0833, $city->getLatitude());
        $this->assertEquals(2.4, $city->getLongitude());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::findNearestCityByNameAndCoordinates
     *
     * @return void
     */
    public function testFindOneCityByNameAndCoordinatesUnknownName()
    {
        $cityUtility = $this->getContainer()->get('chaplean_location.city_utility');
        $city = $cityUtility->findNearestCityByNameAndCoordinates('Test', 47.0833, 2.4);

        $this->assertNull($city);
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::findNearestCityByNameAndCoordinates()
     *
     * @return void
     */
    public function testFindOneCityByNameAndCoordinatesWithSameName()
    {
        $cityUtility = $this->getContainer()->get('chaplean_location.city_utility');
        $city = $cityUtility->findNearestCityByNameAndCoordinates('Bordeaux', 44.83, -0.57);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('Bordeaux', $city->getName());
        $this->assertEquals(44.8333, $city->getLatitude());
        $this->assertEquals(-0.566667, $city->getLongitude());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::findNearestCityByNameAndCoordinates()
     *
     * @return void
     */
    public function testFindOneCityByNameAndCoordinatesWithSameCoordinates()
    {
        $cityUtility = $this->getContainer()->get('chaplean_location.city_utility');
        $city = $cityUtility->findNearestCityByNameAndCoordinates('Fausse-Ville', 44.83, -0.57);

        $this->assertInstanceOf(City::class, $city);
        $this->assertEquals('Fausse-Ville', $city->getName());
        $this->assertEquals(44.8333, $city->getLatitude());
        $this->assertEquals(-0.566667, $city->getLongitude());
    }
}
