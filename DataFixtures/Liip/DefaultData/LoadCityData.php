<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\Liip\DefaultData;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadCityData.php.
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
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $fileCity = new CsvReader(__DIR__ . '/../../../Tests/Resources/doc/cities_test.csv', ';', 0);

        $cities = $fileCity->get();

        $cpt = 1;
        foreach ($cities as $cityTxt) {
            $name = str_replace('"', '', ucwords($cityTxt[1]));
            $zipcode = str_replace('"', '', $cityTxt[2]);

            $city = new City();
            $city->setName($name);
            $city->setZipcode($zipcode);
            $city->setLatitude((float) str_replace('"', '', $cityTxt[4]));
            $city->setLongitude((float) str_replace('"', '', $cityTxt[3]));
            $city->setDepartment($this->getReference('department-' . str_replace('"', '', $cityTxt[0])));

            $this->setReference('city-' . $cpt, $city);
            $this->setReference('city-' . strtolower($name) . '-' . $zipcode, $city);

            $manager->persist($city);

            $cpt++;
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
            'Chaplean\Bundle\LocationBundle\DataFixtures\Liip\DefaultData\LoadDepartmentData'
        );
    }
}
