<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\Liip\DefaultData;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\Region;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRegionData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class LoadRegionData extends AbstractFixture
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
        $fileRegion = new CsvReader(__DIR__ . '/../../../Tests/Resources/doc/regions_test.csv');
        $regions = $fileRegion->extractData(';');

        foreach ($regions as $regionTxt) {
            $region = new Region();
            $region->setCode($regionTxt[0]);
            $region->setName(ucwords($regionTxt[1]));

            $manager->persist($region);

            $this->setReference('region-' . $regionTxt[0], $region);
        }

        $manager->flush();
    }
}
