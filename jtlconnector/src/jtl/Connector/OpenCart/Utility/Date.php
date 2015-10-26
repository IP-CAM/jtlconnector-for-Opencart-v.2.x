<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Utility;

class Date
{
    public static function isOpenTimeFrame($start, $end)
    {
        return self::isOpenDate($start) && self::isOpenDate($end);
    }

    public static function isOpenDate($date)
    {
        $date = preg_replace("/[^1-9]/", "", $date);
        return empty($date);
    }

    public static function between($date, $start, $end)
    {
        return ($date > $start) && ($date < $end);
    }
}