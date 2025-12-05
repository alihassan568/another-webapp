<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function toDate(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? $date->format('Y-m-d')
            : '';
    }

    public static function toDateTime(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? $date->format('Y-m-d H:i:s')
            : '';
    }

    public static function toHumanDiff(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? $date->diffForHumans()
            : '';
    }

    public static function toWeekDayFirstLetter(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? substr($date->format('D'), 0, 3)
            : '';
    }

    public static function toDateDigit(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? $date->format('d')
            : '';
    }

    public static function toFormattedDate(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? $date->format('M d, Y')
            : '';
    }

    public static function toTime(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? $date->format('H:i')
            : '';
    }

    public static function toISOString(?Carbon $date = null): string
    {
        return (! is_null($date))
            ? $date->toISOString()
            : '';
    }
}