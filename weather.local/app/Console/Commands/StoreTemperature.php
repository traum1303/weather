<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Temperature;
use App\Services\OpenWeatherMapApiService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class StoreTemperature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:store-temperature';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        $cityName = env('CITY');
        switch (env('WEATHER_API_SERVICE', 'open_weather_map'))
        {
            case 'open_weather_map':
                try {
                    $cityGeoData = City::query()
                        ->where('city_name', 'LIKE', "%{$cityName}%")
                        ->firstOrFail(['id', 'latitude', 'longitude'])
                        ->toArray();
                } catch (ModelNotFoundException $exception) {
                    $cityGeoData = OpenWeatherMapApiService::getCityGeoData($cityName);

                    $city = City::query()->create([
                        'longitude' => $cityGeoData[0]['lon'],
                        'latitude' => $cityGeoData[0]['lat'],
                        'city_name' => $cityName
                    ]);

                    $cityGeoData['id'] = $city->id;
                    $cityGeoData['latitude'] = $cityGeoData[0]['lat'];
                    $cityGeoData['longitude'] = $cityGeoData[0]['lon'];
                }
                $weatherData = OpenWeatherMapApiService::getWeather($cityGeoData);
                $temperature = $weatherData['main']['temp'];

                break;
            case 'another_weather_api':
                // logic
        }

        if (!isset($temperature))
        {
            throw new Exception('The city was not found based on your search criteria.', Response::HTTP_NO_CONTENT);
        }

        Temperature::query()->create([
            'city_id' => $cityGeoData['id'],
            'temperature' => $temperature
        ]);
    }
}
