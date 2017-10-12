<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\LocationBundle\Utility\CityUtility;
use PHPUnit\Framework\TestCase;

/**
 * Class CityUtility.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class CityUtilityTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::getDepartmentCodeFromZipcode()
     *
     * @return void
     */
    public function testgetDepartmentCodeFromZipcode()
    {
        $this->assertEquals('18', CityUtility::getDepartmentCodeFromZipcode('18562'));
        $this->assertEquals('999', CityUtility::getDepartmentCodeFromZipcode('99999'));
        $this->assertEquals('1', CityUtility::getDepartmentCodeFromZipcode('1'));
        $this->assertEquals('97', CityUtility::getDepartmentCodeFromZipcode('97'));
        $this->assertEquals('', CityUtility::getDepartmentCodeFromZipcode(''));
        $this->assertEquals('2A', CityUtility::getDepartmentCodeFromZipcode('20'));
        $this->assertEquals('2B', CityUtility::getDepartmentCodeFromZipcode('21'));
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\CityUtility::extractLatitudeLongitude()
     *
     * @return void
     */
    public function testExtractLongitudeLatitude()
    {
        $this->assertEquals(['longitude' => 40.23, 'latitude' => 5.64], CityUtility::extractLatitudeLongitude('40.23, 5.64'));

        $coords = CityUtility::extractLatitudeLongitude('');
        $this->assertSame(0, $coords['longitude']);
        $this->assertSame(0, $coords['latitude']);

        $coords = CityUtility::extractLatitudeLongitude('a,a');
        $this->assertSame(0, $coords['longitude']);
        $this->assertSame(0, $coords['latitude']);
    }
}
