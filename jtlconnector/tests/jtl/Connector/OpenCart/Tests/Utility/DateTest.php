<?php

namespace jtl\Connector\OpenCart\Tests\Utility;

use jtl\Connector\OpenCart\Utility\Date;
use PHPUnit_Framework_TestCase;

class DateTest extends PHPUnit_Framework_TestCase
{
    public function testIsOpenTimeFrame()
    {
        $this->assertFalse(Date::isOpenTimeFrame('2015-10-23 11:47:43', ''));

        $this->assertTrue(Date::isOpenTimeFrame('0000-00-00', '0000-00-00 00:00:00'));
    }

    public function testIsOpenDate()
    {
        $this->assertFalse(Date::isOpenDate('0000-00-01'));
        $this->assertFalse(Date::isOpenDate(date('Y-m-d H:i:s')));

        $this->assertTrue(Date::isOpenDate(''));
        $this->assertTrue(Date::isOpenDate(null));
        $this->assertTrue(Date::isOpenDate('0000-00'));
        $this->assertTrue(Date::isOpenDate('0000-00-00'));
        $this->assertTrue(Date::isOpenDate('0000-00-00 00:00:00'));
    }

    public function testBetween()
    {
        $date = date('Y-m-d H:i:s');
        $start = '2015-10-23 11:47:43';
        $end = '2015-10-24 11:47:43';
        $this->assertFalse(Date::between($date, $start, $end));

        $end = $date + 1000;
        $this->assertTrue(Date::between($date, $start, $end));
    }
}
