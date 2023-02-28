<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $request = [
            "email" => "anthony.morah11@gmail.com",
            "password" => "12345"
        ];

        $response = $this->post('/api/authentication/login',$request,[]);
        print($response->content());
        $response->assertStatus(200);
    }
}
