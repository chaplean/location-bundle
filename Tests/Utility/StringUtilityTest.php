<?php

namespace Tests\Chaplean\Bundle\LocationBundle\Utility;

use Chaplean\Bundle\LocationBundle\Utility\StringUtility;
use PHPUnit\Framework\TestCase;

/**
 * Class StringUtilityTest.
 *
 * @package   Tests\Chaplean\Bundle\LocationBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     7.0.0
 */
class StringUtilityTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\LocationBundle\Utility\StringUtility::removeAccent()
     *
     * @return void
     */
    public function testRemoveAccent()
    {
        $string = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïñòóôõöøùúûüýÿĀāĂăĄąĆćĈĉĊċ' . 'ČčĎďĐđĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħĨĩĪīĬĭĮįİıĲĳĴĵĶķĹĺĻļĽľĿŀŁłŃńŅņŇňŉŌōŎŏŐőŒœŔŕ' .
            'ŖŗŘřŚśŜŝŞşŠšŢţŤťŦŧŨũŪūŬŭŮůŰűŲųŴŵŶŷŸŹźŻżŽžſƒƠơƯưǍǎǏǐǑǒǓǔǕǖǗǘǙǚǛǜǺǻǼǽǾǿ';
        $stringWithoutAccent = 'AAAAAAAECEEEEIIIIDNOOOOOOUUUUYsaaaaaaaeceeeeiiiinoooooouuuuyyAaAaAaCcCcCc' .
            'CcDdDdEeEeEeEeEeGgGgGgGgHhHhIiIiIiIiIiIJijJjKkLlLlLlLlLlNnNnNnnOoOoOoOEoeR' .
            'rRrRrSsSsSsSsTtTtTtUuUuUuUuUuUuWwYyYZzZzZzsfOoUuAaIiOoUuUuUuUuUuAaAEaeOo';
        $this->assertEquals($stringWithoutAccent, StringUtility::removeAccent($string));
    }
}
