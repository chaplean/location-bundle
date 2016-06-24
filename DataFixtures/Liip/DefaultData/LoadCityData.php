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
        $fileCity = new CsvReader(__DIR__ . '/../../../Tests/Resources/doc/cities_test.csv');

        $cities = $fileCity->extractData(';');

        $cpt = 1;
        foreach ($cities as $cityTxt) {
            $city = new City();
            $city->setName(str_replace('"', '', ucwords($cityTxt[1])));
            $city->setZipcode(str_replace('"', '', $cityTxt[2]));
            $city->setDepartment($this->getReference('department-' . str_replace('"', '', $cityTxt[0])));

            $this->setReference('city-' . $cpt, $city);
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
