<?php

namespace Chaplean\Bundle\LocationBundle\DataFixtures\ORM;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\Region;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRegionData.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
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
        $fileRegion = new CsvReader(__DIR__ . '/../../Resources/doc/regions_2016.csv', ';', 0);
        $fileRegionCom = new CsvReader(__DIR__ . '/../../Resources/doc/com_regions_2016.csv', ';', 0);

        $regions = array_merge($fileRegion->get(), $fileRegionCom->get());

        foreach ($regions as $reg) {
            $region = new Region();
            $region->setCode($reg[0]);
            $region->setName(ucwords($reg[1]));
            $manager->persist($region);
            $this->setReference('region-' . $reg[0], $region);
        }

        $manager->flush();
    }

    /**
     * @param string $oldCode
     *
     * @return string
     */
    public static function getNewCodeRegion($oldCode)
    {
        $oldToNewCode = array(
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '06' => '06',
            '11' => '11',
            '21' => '44',
            '22' => '32',
            '23' => '28',
            '24' => '24',
            '25' => '28',
            '26' => '27',
            '31' => '32',
            '41' => '44',
            '42' => '44',
            '43' => '27',
            '52' => '52',
            '53' => '53',
            '54' => '75',
            '72' => '75',
            '73' => '76',
            '74' => '75',
            '82' => '84',
            '83' => '84',
            '91' => '76',
            '93' => '93',
            '94' => '94',
            '99' => '99',
        );

        return 'region-' . $oldToNewCode[$oldCode];
    }
}
