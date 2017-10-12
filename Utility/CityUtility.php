<?php

namespace Chaplean\Bundle\LocationBundle\Utility;

/**
 * Class CityUtility.
 *
 * @package   Chaplean\Bundle\LocationBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class CityUtility
{
    /**
     * Extract department code from zipcode
     *
     * @param string $zipcode
     *
     * @return string
     */
    public static function getDepartmentCodeFromZipcode($zipcode)
    {
        $code = substr($zipcode, 0, 2);

        if (((int) $code) > 96) {
            $code = substr($zipcode, 0, 3);
        } elseif (((int) $code) === 20) {
            $code = str_replace('0', 'A', $code);
        } elseif (((int) $code) === 21) {
            $code = str_replace('1', 'B', $code);
        }

        return $code;
    }

    /**
     * Extract longitude and latitude from string (format: xx.xx,xx.xx)
     *
     * @param string $gpsCoordinates
     *
     * @return array
     */
    public static function extractLatitudeLongitude($gpsCoordinates)
    {
        $coords = explode(',', $gpsCoordinates);

        if (count($coords) < 2 || !is_numeric($coords[0]) || !is_numeric($coords[1])) {
            return [
                'longitude' => 0,
                'latitude'  => 0
            ];
        }

        return [
            'longitude' => (float) $coords[0],
            'latitude'  => (float) $coords[1]
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function reformatName($name)
    {
        return preg_replace('/^SAINT(E?\s{1})/', 'ST$1', str_replace(['-', '\''], ' ', strtoupper(StringUtility::removeAccent($name))));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function removeNumber($name)
    {
        return trim(preg_replace('/\d/', '', $name));
    }
}
