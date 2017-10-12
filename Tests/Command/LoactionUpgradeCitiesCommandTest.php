<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Command;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * Class LoactionUpgradeCitiesCommandTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Command
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class LoactionUpgradeCitiesCommandTest extends LogicalTestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Command\LoactionUpgradeCitiesCommand::configure()
     * @covers \Chaplean\Bundle\LocationBundle\Command\LoactionUpgradeCitiesCommand::execute()
     *
     * @return void
     */
    public function testRunCommand()
    {
        $output = $this->runCommand('location:upgrade:cities', [], true);

        $this->assertRegExp('/Finished/', $output);
        $this->assertRegExp('/Cities added/', $output);
    }
}
