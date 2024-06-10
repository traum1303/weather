<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\WeatherApiInterface;
use Illuminate\Support\Facades\Http;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class OpenWeatherMapApiService implements WeatherApiInterface
{

    public static function getWeather(array $requestData): array
    {
        $config = config('services.open_weather_map');
        $baseUrl = $config['base_url'] ?? 'https://api.openweathermap.org';
        $version = $config['endpoints']['versions']['weather'] ?? '2.5';
        $url = "{$baseUrl}/data/{$version}/weather";

        $response = Http::get($url, [
            'lat' => $requestData['latitude'],
            'lon' => $requestData['longitude'],
            'appid' => $config['api_key'] ?? env('OPEN_WEATHER_MAP_API_KEY'),
            'units' => 'metric'
        ]);

        if (!$response->successful())
        {
            throw new Exception('The city was not found based on your search criteria.', Response::HTTP_NO_CONTENT);
        }

        $body = $response->json();

        if (!is_array($body) || !isset($body['cod']) || 200 !== $body['cod'])
        {
            throw new Exception('The city was not found based on your search criteria.', Response::HTTP_NO_CONTENT);
        }

        return $body;
    }

    /**
     * @throws Exception
     */
    public static function getCityGeoData(string $cityName): array
    {
        $config = config('services.open_weather_map');
        $baseUrl = $config['base_url'] ?? 'https://api.openweathermap.org';
        $version = $config['endpoints']['versions']['city'] ?? '1.0';
        $url = "{$baseUrl}/geo/{$version}/direct";

        $response = Http::get($url, [
            'q' => $cityName,
            'limit' => 1,
            'appid' => $config['api_key'] ?? env('OPEN_WEATHER_MAP_API_KEY'),
        ]);

        if (!$response->successful())
        {
            throw new Exception('The city was not found based on your search criteria.', Response::HTTP_NO_CONTENT);
        }

        $body = $response->json();

        if (!is_array($body))
        {
            throw new Exception('The city was not found based on your search criteria.', Response::HTTP_NO_CONTENT);
        }

        if (!isset($body[0]))
        {
            throw new Exception('The city was not found based on your search criteria.', Response::HTTP_NO_CONTENT);
        }

        return $body;
    }
}
