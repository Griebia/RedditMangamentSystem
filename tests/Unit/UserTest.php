<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    private $token = 0; 
    private $randomInt = 0;


    public function testGetOneUser()
    {
        $response = $this->get('/api/user/5');

        $response->assertStatus(400);
    }

    
    public function testLoginWithBadCredentials(){
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'badEmail@gmail.com', 'password'=> 'test123'
        ])->assertStatus(401);
    }
    public function testRegisterWithBadForm(){
        $response = $this->json('POST', '/api/register', [
            'name'=> 'tests' ,
            'email'=> 'test',
            'password'=> 'test123'
        ])
            ->assertStatus(400);
    }


    public function testLoginWithExpiredToken(){
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zdHBwLnRlc3RcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE1NzM2MzY5NDUsImV4cCI6MTU3MzY0MDU0NSwibmJmIjoxNTczNjM2OTQ1LCJqdGkiOiJmRFljRnJDMzlVbjV2NTJoIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.D3xZnHxhgUjYQisSQLQw8IkxhJiSABqknix-YvbboF4';
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('POST', '/api/user', [
            'password'=> 'test123'
        ])
            ->assertStatus(400);
    }
    public function testLoginWithBadToken(){
        $token = 'eyJ0eXAiOiJKV1QisdsLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zdHBwLnRlc3RcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE1NzM2MzY5NDUsImV4cCI6MTU3MzY0MDU0NSwibmJmIjoxNTczNjM2OTQ1LCJqdGkiOiJmRFljRnJDMzlVbjV2NTJoIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.D3xZnHxhgUjYQisSQLQw8IkxhJiSABqknix-YvbboF4';
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('POST', '/api/user', [
            'password'=> 'test123'
        ])
            ->assertStatus(400);
    }


    //----------------------USER----------------------
    //DOne
    public function testRegisterUser(){
        global $randomInt;
        $randomInt = rand(30, 100000);
        //fwrite(STDERR, print_r($randomInt, TRUE));
        $response = $this->json('POST', '/api/register', [
                'name'=> 'test' . $randomInt,
                'email'=> 'test' . $randomInt . '@gmail.com',
                'password'=> 'test123'
            ])
                ->assertStatus(200);
    }
    //Done
    public function testLoginUser(){
        global $randomInt;
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'test' . $randomInt . '@gmail.com', 'password'=> 'test123'
        ])->assertOk();

        global $token;
        //$this->setToken($response->json()['access_token']);
        $token = $response->json()['access_token'];
        //fwrite(STDERR, print_r($token, TRUE));
    }


    public function testMeuser(){
        global $randomInt;
        global $token;
        global $id;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('POST', '/api/auth/me')->assertOk();
        $id = $response->json()['id'];
    }

    //Done
    public function testRefreshToken()
    {  
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                        ->json('POST', '/api/auth/refresh', [
            'email' => 'test' . $randomInt . '@gmail.com', 'password'=> 'test123'
        ])->assertOk();


        //$this->setToken($response->json()['access_token']);
        $token = $response->json()['access_token'];
        //fwrite(STDERR, print_r($token, TRUE));
    }
    //Done
    public function testGetAllUsersNotAdmin()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                        ->json('GET', '/api/users')
                        ->assertStatus(403);

        
    }
    //Done
    public function testCreateNewUserNotAdmin()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('POST', '/api/user', [
            'name'=> 'test' . $randomInt,
            'email'=> 'test' . $randomInt . '@gmail.com',
            'password'=> 'test123'
        ])
            ->assertStatus(401);
    }
    //Done
    public function testGetOneUserLogedIn()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])->get('/api/user/5');

        $response->assertStatus(403);
    }
    //Done
    public function testUpdateUser()
    {
        global $randomInt;
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('PUT', '/api/user', [
            'name'=> 'testUpdate',
            'email'=> 'testNew' . $randomInt . '@gmail.com',
            'password'=> 'test123'
        ])
            ->assertStatus(200);
    }
    //Done
    public function testDeleteUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->delete('/api/user/17')
        ->assertStatus(403);

    }

    function testRusersUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/userrusers')
        ->assertStatus(200);
    }

    //Done
    public function testLogoutUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                        ->json('Post', '/api/auth/logout')
                        ->assertStatus(200);
    }

    //-------------------ADMIN-------------------------
    public function testRegisterAdmin(){
        global $randomInt;
        $randomInt = rand(0, 100000);
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'admin@admin.com', 'password'=> 'admin'
        ]);

        //$this->setToken($response->json()['access_token']);
        $token = $response->json()['access_token'];
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('POST', '/api/user', [
                'name'=> 'admin' . $randomInt,
                'email'=> 'admin' . $randomInt . '@gmail.com',
                'password'=> 'admin',                                           
                'is_admin'=> 1
            ])
                ->assertStatus(201);
        //$response = $this->json('POST', '/api/register', ['name' => 'Sally'], ['HTTP_Authorization' => $this->token]);
    }

    public function testLoginAdmin(){
        global $randomInt;

        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'admin' . $randomInt . '@gmail.com', 'password'=> 'admin'
        ])->assertOk();

        global $token;
        //$this->setToken($response->json()['access_token']);
        $token = $response->json()['access_token'];
        //fwrite(STDERR, print_r($token, TRUE));
    }

    public function testGetAllUsersAdmin()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                        ->json('GET', '/api/users')
                        ->assertStatus(200);
    }
    public function testGetOneUsersAdmin()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                        ->json('GET', '/api/user/1')
                        ->assertStatus(200);
    }
    //Done
    public function testCreateNewUserAdmin()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('POST', '/api/user', [
            'name'=> 'testNew' . ($randomInt + 1),
            'email'=> 'testNew' . ($randomInt + 1) . '@gmail.com',
            'password'=> 'test123',
            'is_admin'=> 0
        ])
            ->assertStatus(201);
    }

    public function testDeleteUserAdmin()
    {
        global $token;
        global $id;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->delete('/api/user/'.$id)
        ->assertStatus(200);
    }

     //Done
    public function testLogoutAdmin()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                        ->json('Post', '/api/auth/logout')
                        ->assertStatus(200);
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
