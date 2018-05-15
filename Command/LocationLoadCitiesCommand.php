<?php

namespace Chaplean\Bundle\LocationBundle\Command;

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
        $regionUtility = $container->get('chaplean_location.region_utility');
        $departmentUtility = $container->get('chaplean_location.department_utility');
        $cityUtility = $container->get('chaplean_location.city_utility');

        $regionUtility->loadRegion();
        $output->writeln('Regions added');

        $departmentUtility->loadDepartment();
        $output->writeln('Departments added');

        $cityUtility->loadCities();
        $output->writeln('Cities added');
    }
}
