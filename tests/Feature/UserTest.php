<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function initiateEnrollment()
    {
        $request = [
            "email" => "anthony.morah11@gmail.com",
            "password" => "12345"
        ];

        $response = $this->post('/api/authentication/initiate-enrollment',$request,[]);
        print($response->content());
        $response->assertStatus(200);
    }

    public function completeEnrollment()
    {
        $request = [
            "email" => "anthony.morah11@gmail.com",
            "password" => "12345"
        ];

        $response = $this->post('/api/authentication/complete-enrollment',$request,[]);
        print($response->content());
        $response->assertStatus(200);
    }

    public function login()
    {
        $request = [
            "email" => "anthony.morah11@gmail.com",
            "password" => "12345"
        ];

        $response = $this->post('/api/authentication/login',$request,[]);
        print($response->content());
        $response->assertStatus(200);
    }

    public function userDetails()
    {
        $response = $this->get('/api/authentication/user-details',[]);
        print($response->content());
        $response->assertStatus(200);
    }

    public function initiatePasswordReset()
    {
        $request = [
            "email" => "anthony.morah11@gmail.com",
            "password" => "12345"
        ];

        $response = $this->post('/api/authentication/initiate-password-reset',$request,[]);
        print($response->content());
        $response->assertStatus(200);
    }

    public function completePasswordReset()
    {
        $request = [
            "email" => "anthony.morah11@gmail.com",
            "password" => "12345"
        ];

        $response = $this->post('/api/authentication/complete-password-reset',$request,[]);
        print($response->content());
        $response->assertStatus(200);
    }

    public function resendOtp()
    {
        $request = [
            "email" => "anthony.morah11@gmail.com",
            "password" => "12345"
        ];

        $response = $this->post('/api/authentication/resend-otp',$request,[]);
        print($response->content());
        $response->assertStatus(200);
    }

    public function logout()
    {
        $response = $this->get('/api/authentication/logout');
        print($response->content());
        $response->assertStatus(200);
    }
}
