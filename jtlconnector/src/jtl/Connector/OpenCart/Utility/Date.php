<?php

namespace jtl\Connector\OpenCart\Utility;

class Date
{
    public static function is_open_time_frame($start, $end)
    {
        return self::open_date($start) && self::open_date($end);
    }

    public static function open_date($date)
    {
        return ($date === '0000-00-00' || $date === '0000-00-00 00:00:00' || is_null($date));
    }

    public static function between($date, $start, $end)
    {
        return ($date > $start) && ($date < $end);
    }
}