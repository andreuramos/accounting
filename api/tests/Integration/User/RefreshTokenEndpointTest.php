<?php

namespace Test\Integration\User;

use Test\Integration\EndpointTest;

class RefreshTokenEndpointTest extends EndpointTest
{
    public function test_refresh_with_no_params_returns_400()
    {
        $response = $this->client->post('/refresh');

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_not_a_jwt_token_returns_401()
    {
        $response = $this->client->post('/refresh', [
            'body' => json_encode([
                'refresh_token' => "Notevenajwt"
            ], JSON_THROW_ON_ERROR)
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
