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
class LocationUpgradeCitiesCommand extends ContainerAwareCommand
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $locationUpgradeCitiesUtility = $this->getContainer()->get('chaplean_location.location_upgrade_cities.utility');

        $citiesAdded = 0;
        $cityRepository = $em->getRepository('ChapleanLocationBundle:City');
        $departmentRepository = $em->getRepository('ChapleanLocationBundle:Department');

        $output->writeln('Find all cities...</info>');
        $oldCities = $cityRepository->findAll();

        $output->writeln('<info>Rename cities...</info>');

        if (!empty($oldCities)) {
            $progress = new ProgressBar($output, count($oldCities));
            $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
            $progress->start();

            /** @var City $oldCity */
            foreach ($oldCities as $i => $oldCity) {
                $oldCity->setName(CityUtility::reformatName($oldCity->getName()));

                $em->persist($oldCity);
                $progress->advance();

                if ($i % 100 === 0) {
                    $em->flush();
                }
            }

            $em->flush();
            $progress->finish();
            $output->writeln("\nRename done <info>✔</info>");
        }

        $newCities = $locationUpgradeCitiesUtility->getNewCities();

        $output->writeln('<info>Upgrade cities...</info>');
        $progress = new ProgressBar($output);
        $progress->setFormat(' %current% [%bar%] %elapsed:6s% %memory:6s%');
        $progress->start();

        $inserted = [];

        foreach ($newCities as $newCity) {
            $name = CityUtility::removeNumber($newCity[1]);
            $zipcode = $newCity[2];
            $hash = $name . $zipcode;

            if (isset($inserted[$hash]) || $locationUpgradeCitiesUtility->isCityExisting($name, $zipcode)) {
                continue;
            }

            $code = CityUtility::getDepartmentCodeFromZipcode($zipcode);
            $coords = CityUtility::extractLatitudeLongitude($newCity[5]);
            $department = $departmentRepository->findOneByCode($code);

            if ($department === null) {
                $output->writeln(sprintf("\nDepartment not found: %s", $code));
                continue;
            }

            $city = new City();
            $city->setName($name);
            $city->setZipcode($zipcode);
            $city->setLatitude($coords['latitude']);
            $city->setLongitude($coords['longitude']);
            $city->setDepartment($department);

            $em->persist($city);

            $inserted[$hash] = true;

            $citiesAdded++;
            if ($citiesAdded % 250 === 0) {
                $em->flush();
            }

            $progress->advance();
        }

        $em->flush();

        $progress->finish();
        $output->writeln("\nFinished <info>✔</info>");
        $output->writeln(sprintf('Cities added <info>%d</info>', $citiesAdded));
    }
}
