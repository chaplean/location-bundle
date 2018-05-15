<?php

namespace Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
     * @var EntityManager
     */
    private $em;

    /**
     * CityUtility constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    public function loadCities()
    {
        $fileCity = new CsvReader(__DIR__ . '/../Resources/doc/cities_2017.csv', ';', true);
        $cities = $fileCity->get();

        $cityRepository = $this->em->getRepository('ChapleanLocationBundle:City');
        $departmentRepository = $this->em->getRepository('ChapleanLocationBundle:Department');

        foreach ($cities as $cityTxt) {
            $name = CityUtility::removeNumber($cityTxt[1]);
            $zipcode = $cityTxt[2];
            $departmentCode = CityUtility::getDepartmentCodeFromZipcode($zipcode);
            $coords = CityUtility::extractLatitudeLongitude($cityTxt[5]);

            $department = $departmentRepository->findOneByCode($departmentCode);

            if ($cityRepository->findOneBy(['name' => $name, 'zipcode' => $zipcode]) !== null || $department === null) {
                continue;
            }

            $city = new City();
            $city->setName($name);
            $city->setZipcode($zipcode);
            $city->setLatitude($coords['latitude']);
            $city->setLongitude($coords['longitude']);
            $city->setDepartment($department);
            $this->em->persist($city);
        }
    }

    /**
     * Return a city according to the given name and coordinates.
     *
     * @param string $name      City's name
     * @param float  $latitude  City's latitude
     * @param float  $longitude City's longitude
     *
     * @return City|null
     */
    public function findNearestCityByNameAndCoordinates($name, $latitude, $longitude)
    {
        $distance = null;
        $city = null;
        $cities = $this->em->getRepository(City::class)->findBy(['name' => $name], [], 20);

        /** @var City $result */
        foreach ($cities as $result) {
            $cityLatitude = $result->getLatitude();
            $cityLongitude = $result->getLongitude();

            $cityDistance = 3959 * acos(cos(deg2rad($latitude)) * cos(deg2rad($cityLatitude)) * cos(deg2rad($cityLongitude) - deg2rad($longitude)) + sin(deg2rad($latitude)) * sin(deg2rad($cityLatitude)));

            if ($distance === null || $cityDistance < $distance) {
                $city = $result;
                $distance = $cityDistance;
            }
        }

        return $city;
    }
    
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
