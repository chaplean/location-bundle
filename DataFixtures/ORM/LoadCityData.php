<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Utility\CityUtility;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRegionData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class LoadCityData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $fileCity = new CsvReader(__DIR__ . '/../../Resources/doc/cities_2017.csv', ';', true);
        $cities = $fileCity->get();

        $cityRepository = $manager->getRepository('ChapleanLocationBundle:City');

        foreach ($cities as $cityTxt) {
            $name = ucwords(strtolower($cityTxt[1]));
            $zipcode = $cityTxt[2];
            $department = CityUtility::getDepartmentCodeFromZipcode($zipcode);
            $coords = CityUtility::extractLatitudeLongitude($cityTxt[5]);

            if ($cityRepository->findOneBy(['name' => $name, 'zipcode' => $zipcode]) !== null) {
                continue;
            }

            $city = new City();
            $city->setName($name);
            $city->setZipcode($zipcode);
            $city->setLatitude($coords['latitude']);
            $city->setLongitude($coords['longitude']);
            $city->setDepartment($this->getReference('department-' . $department));
            $manager->persist($city);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadDepartmentData::class
        ];
    }
}
