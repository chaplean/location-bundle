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
        $codeInt = (int) $code;

        if ($codeInt > 96) {
            $code = substr($zipcode, 0, 3);
        } elseif ($codeInt === 20) {
            $code = str_replace('0', 'A', $code);
        } elseif ($codeInt === 21) {
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
            'latitude'  => (float) $coords[0],
            'longitude' => (float) $coords[1]
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function reformatName($name)
    {
        return preg_replace('/(^S|\s{1}S)AINT(E?\s{1})/', '$1T$2', str_replace(['-', '\''], ' ', strtoupper(StringUtility::removeAccent($name))));
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
