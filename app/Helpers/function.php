<?php

use App\Models\Post;
use App\Enums\UserRoleEnum;
use App\Enums\SystemCacheKeyEnum;

if (!function_exists('getRoleByKey')) {
    function getRoleByKey($key): string
    {
        return strtolower(UserRoleEnum::getKeys((int)$key)[0]);
    }
}

if (!function_exists('user')) {
    function user(): ?object
    {
        return auth()->user();
    }
}

if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin(): bool
    {
        return user() && user()->role === UserRoleEnum::SUPER_ADMIN;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return user() && user()->role === UserRoleEnum::ADMIN;
    }
}

if (!function_exists('getAndCachePostCities')) {
    function getAndCachePostCities(): array
    {
        return cache()->remember(
            SystemCacheKeyEnum::POST_CITIES,
            86400 * 30,
            function () {
                $cities  = Post::query()
                    ->pluck('city');
                $arrCity = [];
                foreach ($cities as $city) {
                    if (empty($city)) {
                        continue;
                    }
                    $arr = explode(', ', $city);
                    foreach ($arr as $item) {
                        if (empty($item)) {
                            continue;
                        }
                        if (in_array($item, $arrCity)) {
                            continue;
                        }
                        $arrCity[] = $item;
                    }
                }

                return $arrCity;
            }
        );
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol($string)
    {
        $symbol = '';
        $length = mb_strlen($string, 'utf-8');
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($string, $i, 1, 'utf-8');
            if (!ctype_digit($char) && !ctype_punct($char))
                $symbol .= $char;
        }
        return $symbol;
    }
}


if (!function_exists('removeLastCharacter')) {
    function removeLastCharacter($string, $count = null)
    {
        if (is_null($count)) $count = 1;
        for ($i = 0; $i < $count; $i++) {
            $string = substr_replace($string, "", -1);
        }
        return $string;
    }
}

if (!function_exists('formatCurrencySalary')) {
    function formatCurrencySalary($string, $key)
    {
        if ($key == 'USD' || $key == 'EUR' || $key == 'CNY') {
            $string = removeLastCharacter($string, 3);
        }
        return $string;
    }
}
