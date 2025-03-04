<?php

use Carbon\Carbon;

if (!function_exists('carbon')) {
    function carbon($date = null)
    {
        if (!empty($date)) {
            if ($date instanceof DateTime) {
                return Carbon::instance($date);
            }

            return Carbon::parse(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $date))));
        }

        return Carbon::now();
    }
}

if (!function_exists('usDate')) {
    function usDate($datetime = null, $timestamp = false)
    {
        $datetime = $datetime ? $datetime : Carbon::now();
        $format = $timestamp ? 'd/m/Y H:i' : 'd/m/Y';
        $timestamp = $timestamp ? 'Y-m-d H:i:s' : 'Y-m-d';
        return Carbon::createFromFormat($format, $datetime)->format($timestamp);
    }
}

if (!function_exists('brDate')) {
    function brDate($datetime = null, $timestamp = false)
    {
        $datetime = $datetime ? $datetime : Carbon::now();
        $timestamp = $timestamp ? 'd/m/Y H:i' : 'd/m/Y';
        return Carbon::parse($datetime)->format($timestamp);
    }
}

if (!function_exists('floatToMoney')) {
    function floatToMoney($value)
    {
        return number_format($value, 2, ',', '.');
    }
}

if (!function_exists('moneyToFloat')) {
    function moneyToFloat($value)
    {
        if (!$value) return 0;

        $source = array('.', ',');
        $replace = array('', '.');
        return str_replace($source, $replace, $value);
    }
}

if (!function_exists('removeMask')) {
    function removeMask($str)
    {
        if (!$str) {
            return $str;
        }

        return preg_replace('/[^A-Za-z0-9]/', '', $str);
    }
}

if (!function_exists('insertMask')) {
    function insertMask($str, $mask)
    {
        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}

if (!function_exists('nifMask')) {
    function nifMask($str)
    {
        if (strlen($str) == 11) {
            $mask = '###.###.###-##';
        } else {
            $mask = '##.###.###/####-##';
        }

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}

if (!function_exists('phoneMask')) {
    function phoneMask($str)
    {
        $mask = '(##) #####-####';

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}

if (!function_exists('zipCodeMask')) {
    function zipCodeMask($str)
    {
        $mask = '#####-###';

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
}
