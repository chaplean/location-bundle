<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRegionData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
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
        $fileCity = new CsvReader(__DIR__ . '/../../Resources/doc/cities_2014.csv');
        $cities = $fileCity->extractData(',');

        foreach ($cities as $cityTxt) {
            $name = str_replace('"', '', ucwords($cityTxt[5]));
            $zipcode = str_replace('"', '', $cityTxt[8]);
            $latitude = str_replace('"', '', $cityTxt[20]);
            $longitude =str_replace('"', '', $cityTxt[19]);
            $department = str_replace('"', '', $cityTxt[1]);
            $zipcodes = explode('-', $zipcode);

            if (!empty($zipcodes)) {
                foreach ($zipcodes as $zipcode) {
                    $city = new City();
                    $city->setName($name);
                    $city->setZipcode($zipcode);
                    $city->setLatitude($latitude);
                    $city->setLongitude($longitude);
                    $city->setDepartment($this->getReference('department-' . $department));
                    $manager->persist($city);
                }
            } else {
                $city = new City();
                $city->setName($name);
                $city->setZipcode($zipcode);
                $city->setLatitude($latitude);
                $city->setLongitude($longitude);
                $city->setDepartment($this->getReference('department-' . $department));
                $manager->persist($city);
            }
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
        return array(
            'Chaplean\Bundle\LocationBundle\DataFixtures\ORM\LoadDepartmentData',
        );
    }
}
