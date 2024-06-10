<?php

namespace App\Interfaces;

use Exception;

interface WeatherApiInterface
{
    /**
     * @param array $requestData
     * throw
     * @throws Exception
     */
    public static function getWeather(array $requestData): array;
}
