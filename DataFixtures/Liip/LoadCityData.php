<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\Liip;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadCityData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.17.0
 */
class LoadCityData extends AbstractFixture implements OrderedFixtureInterface
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
        $fileCity = new CsvReader(__DIR__ . '/../../Resources/doc/cities_test.csv');

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
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
