<?php

namespace Chaplean\Bundle\LocationBundle\Command;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Utility\CityUtility;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoactionUpgradeCitiesCommand.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class LoactionUpgradeCitiesCommand extends ContainerAwareCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('location:upgrade:cities')
            ->setDescription('Add missing cities compared to version 6.0');
    }

    /**
     * @param InputInterface   $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $fileCity = new CsvReader(__DIR__ . '/../Resources/doc/cities_2017.csv', ';', true);
        $newCities = $fileCity->get();

        $citiesAdded = 0;
        $cityRepository = $em->getRepository('ChapleanLocationBundle:City');
        $departmentRepository = $em->getRepository('ChapleanLocationBundle:Department');

        $progress = new ProgressBar($output, count($newCities));

        $output->writeln('<info>Upgrade cities...</info>');
        $progress->start();

        foreach ($newCities as $newCity) {
            $name = ucwords(strtolower($newCity[1]));
            $zipcode = $newCity[2];

            if ($cityRepository->findOneBy(['name' => $name, 'zipcode' => $zipcode]) === null) {
                $code = CityUtility::getDepartmentCodeFromZipcode($zipcode);
                $department = $departmentRepository->findOneByCode($code);
                $coords = CityUtility::extractLatitudeLongitude($newCity[5]);

                if ($department === null) {
                    $output->writeln(sprintf("\nDepartment not found: %s", $code));
                    $progress->advance();
                    continue;
                }

                $city = new City();
                $city->setName($name);
                $city->setZipcode($zipcode);
                $city->setLatitude($coords['latitude']);
                $city->setLongitude($coords['longitude']);
                $city->setDepartment($department);

                $em->persist($city);

                $citiesAdded++;
                if ($citiesAdded % 500 === 0) {
                    $em->flush();
                }
            }

            $progress->advance();
        }

        $em->flush();

        $progress->finish();
        $output->writeln("\nFinished <info>✔</info>");
        $output->writeln(sprintf('Cities added <info>%d</info>', $citiesAdded));
    }
}
