<?php

namespace Tests\Feature\API;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class AuthTest extends TestCase
{

    public function setUp() : void
    {
        parent::setUp();

    }

    public function tearDown() : void
    {
        parent::tearDown();
    }

    public static function providerFailedAuth(): array
    {
        return [
            [
                [
                    "email" => "fake@test.com",
                    "password" => "fakefake",
                ],
                401,
                [
                    "message" => "Invalid Credentials"
                ]
            ],
            [
                [
                    "email" => "fake@test.com",
                    "password" => "fake",
                ],
                422,
                [
                    "message" => "The password field must be at least 8 characters.",
                    "errors" => [
                        "password" => [
                            0 => "The password field must be at least 8 characters."
                        ]
                    ]
                ]
            ]
        ];
    }


    public static function providerAuth():array
    {
        return [
            [
                [
                    "email" => "test@test.com",
                    "password" => "password",
                    "ability" => "GET_TEMPERATURE"
                ],
                200,
                "access_token"
            ]
        ];
    }

    #[DataProvider('providerAuth')]
    public function testAuth(array $userData, int $code, string $key)
    {
        /**
         * /api/v1/auth
         *
         */
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson( '/api/v1/auth', $userData );

        $response->assertJson(fn(AssertableJson $json) => $json->has($key));
        $response->assertStatus($code);
    }

    #[DataProvider('providerFailedAuth')]
    public function testFailedAuth(array $userData, int $code, array $expected)
    {
        /**
         * /api/v1/auth
         *
         */
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson( '/api/v1/auth', $userData );

        $this->assertEquals($expected, $response->json());
        $response->assertStatus($code);
    }
}
