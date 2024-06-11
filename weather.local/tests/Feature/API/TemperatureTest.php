<?php

namespace API;

use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class TemperatureTest extends TestCase
{

    private string $token;
    public function setUp() : void
    {
        parent::setUp();

        $this->token = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson( '/api/v1/auth', [
                "email" => "test@test.com",
                "password" => "password",
                "ability" => "GET_TEMPERATURE"
            ])->json('access_token');
    }

    public function tearDown() : void
    {
        parent::tearDown();
    }

    public static function providerTemperature():array
    {
        return [
            [now()->format('Y-m-d')]
        ];
    }

    #[DataProvider('providerTemperature')]
    public function testTemperature(string $day)
    {
        /**
         * /api/v1/temperature
         *
         */
        Artisan::call('app:store-temperature');
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->withToken($this->token)
            ->getJson( '/api/v1/temperature?day='.$day);

        $this->assertTrue(isset($response->json()['data'][0]['temperature']));
    }
}
