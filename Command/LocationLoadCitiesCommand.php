<?php

namespace Chaplean\Bundle\LocationBundle\Command;

use Chaplean\Bundle\LocationBundle\Utility\CityUtility;
use Chaplean\Bundle\LocationBundle\Utility\DepartmentUtility;
use Chaplean\Bundle\LocationBundle\Utility\RegionUtility;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LocationLoadCitiesCommand.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     10.0.0
 */
class LocationLoadCitiesCommand extends ContainerAwareCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('location:load:cities')
            ->setDescription('Load Region/Department/Cities');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $em = $container->get('doctrine')->getManager();
        $regionUtility = $container->get(RegionUtility::class);
        $departmentUtility = $container->get(DepartmentUtility::class);
        $cityUtility = $container->get(CityUtility::class);

        $regionUtility->loadRegion();
        $em->flush();
        $em->clear();
        $output->writeln('Regions added');

        $departmentUtility->loadDepartment();
        $em->flush();
        $em->clear();
        $output->writeln('Departments added');

        $cityUtility->loadCities();
        $em->flush();
        $em->clear();
        $output->writeln('Cities added');
    }
}
