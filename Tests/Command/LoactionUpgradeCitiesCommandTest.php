<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Command;

use Chaplean\Bundle\LocationBundle\Command\LocationUpgradeCitiesCommand;
use Chaplean\Bundle\LocationBundle\Entity\City;
use Chaplean\Bundle\LocationBundle\Utility\LocationUpgradeCitiesUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LocationUpgradeCitiesCommandTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Command
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class LocationUpgradeCitiesCommandTest extends MockeryTestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Command\LocationUpgradeCitiesCommand::execute()
     *
     * @return void
     */
    public function testExecute()
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $doctrine = \Mockery::mock(RegistryInterface::class);
        $em = \Mockery::mock(EntityManager::class);
        $locationUpgradeCitiesUtility = \Mockery::mock(LocationUpgradeCitiesUtility::class);
        $repository = \Mockery::mock(EntityRepository::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($em);
        $container->shouldReceive('get')->once()->with('doctrine')->andReturn($doctrine);
        $container->shouldReceive('get')->once()->with('chaplean_location.location_upgrade_cities.utility')
            ->andReturn($locationUpgradeCitiesUtility);

        $em->shouldReceive('getRepository')->once()->andReturn($repository);
        $repository->shouldReceive('findAll')->once()->andReturn([]);

        $locationUpgradeCitiesUtility->shouldReceive('getNewCities')->once()->andReturn([
            ['', 'BORDEAUX', '33000', '', '', ''],
            ['', 'BOURGES', '18000', '', '', '']
        ]);

        $city1 = new City();
        $city1->setName('Foo');

        $city2 = new City();
        $city2->setName('Bar');

        $locationUpgradeCitiesUtility->shouldReceive('isCityExisting')->twice()->andReturnFalse();
        $locationUpgradeCitiesUtility->shouldReceive('createCityFromCsvRow')->twice()->andReturn($city1, $city2);

        $em->shouldReceive('persist')->twice();
        $em->shouldReceive('flush')->once();

        $command = new LocationUpgradeCitiesCommand();
        $command->setContainer($container);

        $command->run(new ArrayInput([]), new TestOutput());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Command\LocationUpgradeCitiesCommand::execute()
     *
     * @return void
     */
    public function testExecuteWithUpdatableCity()
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $doctrine = \Mockery::mock(RegistryInterface::class);
        $em = \Mockery::mock(EntityManager::class);
        $locationUpgradeCitiesUtility = \Mockery::mock(LocationUpgradeCitiesUtility::class);
        $repository = \Mockery::mock(EntityRepository::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($em);
        $container->shouldReceive('get')->once()->with('doctrine')->andReturn($doctrine);
        $container->shouldReceive('get')->once()->with('chaplean_location.location_upgrade_cities.utility')
            ->andReturn($locationUpgradeCitiesUtility);

        $em->shouldReceive('getRepository')->once()->andReturn($repository);

        $city = new City();
        $city->setName('foo');

        $repository->shouldReceive('findAll')->once()->andReturn([$city]);
        $em->shouldReceive('persist')->once()->with($city);
        $em->shouldReceive('flush')->twice();

        $city1 = new City();
        $city1->setName('Foo');

        $city2 = new City();
        $city2->setName('Bar');

        $locationUpgradeCitiesUtility->shouldReceive('getNewCities')->once()->andReturn([
            ['', 'BORDEAUX', '33000', '', '', ''],
            ['', 'BOURGES', '18000', '', '', '']
        ]);

        $locationUpgradeCitiesUtility->shouldReceive('isCityExisting')->twice()->andReturnFalse();
        $locationUpgradeCitiesUtility->shouldReceive('createCityFromCsvRow')->twice()->andReturn($city1, $city2);

        $em->shouldReceive('persist')->twice();
        $em->shouldReceive('flush')->once();

        $command = new LocationUpgradeCitiesCommand();
        $command->setContainer($container);

        $command->run(new ArrayInput([]), new TestOutput());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Command\LocationUpgradeCitiesCommand::execute()
     *
     * @return void
     */
    public function testExecuteWithDoublonInFile()
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $doctrine = \Mockery::mock(RegistryInterface::class);
        $em = \Mockery::mock(EntityManager::class);
        $locationUpgradeCitiesUtility = \Mockery::mock(LocationUpgradeCitiesUtility::class);
        $repository = \Mockery::mock(EntityRepository::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($em);
        $container->shouldReceive('get')->once()->with('doctrine')->andReturn($doctrine);
        $container->shouldReceive('get')->once()->with('chaplean_location.location_upgrade_cities.utility')
            ->andReturn($locationUpgradeCitiesUtility);

        $em->shouldReceive('getRepository')->once()->andReturn($repository);

        $repository->shouldReceive('findAll')->once()->andReturn([]);

        $locationUpgradeCitiesUtility->shouldReceive('getNewCities')->once()->andReturn([
            ['', 'BORDEAUX', '33000', '', '', ''],
            ['', 'BORDEAUX', '33000', '', '', '']
        ]);

        $locationUpgradeCitiesUtility->shouldReceive('isCityExisting')->once()->andReturnFalse();
        $locationUpgradeCitiesUtility->shouldReceive('createCityFromCsvRow')->twice()->andReturn(new City());

        $em->shouldReceive('persist')->once();
        $em->shouldReceive('flush')->once();

        $command = new LocationUpgradeCitiesCommand();
        $command->setContainer($container);

        $command->run(new ArrayInput([]), new TestOutput());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Command\LocationUpgradeCitiesCommand::execute()
     *
     * @return void
     */
    public function testExecuteWithNotFoundDepartment()
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $doctrine = \Mockery::mock(RegistryInterface::class);
        $em = \Mockery::mock(EntityManager::class);

        $locationUpgradeCitiesUtility = \Mockery::mock(LocationUpgradeCitiesUtility::class);
        $repository = \Mockery::mock(EntityRepository::class);

        $doctrine->shouldReceive('getManager')->once()->andReturn($em);
        $container->shouldReceive('get')->once()->with('doctrine')->andReturn($doctrine);
        $container->shouldReceive('get')->once()->with('chaplean_location.location_upgrade_cities.utility')
            ->andReturn($locationUpgradeCitiesUtility);

        $em->shouldReceive('getRepository')->once()->andReturn($repository);

        $repository->shouldReceive('findAll')->once()->andReturn([]);

        $locationUpgradeCitiesUtility->shouldReceive('getNewCities')->once()->andReturn([
            ['', 'BORDEAUX', '33000', '', '', '']
        ]);

        $locationUpgradeCitiesUtility->shouldReceive('isCityExisting')->never();
        $locationUpgradeCitiesUtility->shouldReceive('createCityFromCsvRow')->once()->andThrow(new EntityNotFoundException());

        $em->shouldReceive('persist')->never();
        $em->shouldReceive('flush')->once();

        $command = new LocationUpgradeCitiesCommand();
        $command->setContainer($container);

        $command->run(new ArrayInput([]), new TestOutput());
    }

    /**
     * @covers \Chaplean\Bundle\LocationBundle\Command\LocationUpgradeCitiesCommand::configure()
     *
     * @return void
     */
    public function testConfigure()
    {
        $command = new LocationUpgradeCitiesCommand();

        $this->assertEquals('location:upgrade:cities', $command->getName());
    }
}

class TestOutput extends Output
{
    public $output = '';

    public function clear()
    {
        $this->output = '';
    }

    protected function doWrite($message, $newline)
    {
        $this->output .= $message.($newline ? "\n" : '');
    }
}
