<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RUserTest extends TestCase
{

    //Admin
    //Done
    function testGetAllPostsAdmin()
    {
        global $token;
        global $randomInt;
        $randomInt = rand(0, 100000);
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'admin@admin.com', 'password'=> 'admin'
        ]);

        //$this->setToken($response->json()['access_token']);
        $token = $response->json()['access_token'];

        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('Get', '/api/rusers')
                ->assertStatus(200);
    }
    //Done
    function testCreateNewRuserAdmin()
    {
            global $token;
            global $randomInt;
            $randomInt = rand(30, 100000);
    
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('Post', '/api/connect', [
                'ruser_id' => $randomInt,
                'username' => 'admins',     
                'password' => 'new',
                'user_id'=>'30'
            ])
                ->assertStatus(201);       
    }

    function testUpdateNewRuserAdmin()
    {
            global $token;
            global $randomInt;
    
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('put', '/api/ruser', [
                'ruser_id' => 1,
                'username' => 'adminsnew',     
                'password' => 'new',
                'user_id'=>'30'
            ])
                ->assertStatus(200);       
    }

    function testGetOneRuserAdmin()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/ruser/1')
        ->assertStatus(200);
    }
    function testGetOneRuserPostsAdmin()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/ruserposts/1')
        ->assertStatus(200);
    }

    function testGetOneRuserPostsWrongAdmin()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/ruserposts/5')
        ->assertStatus(403);
    }
    function testDeleteRuserAdmin()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Delete', '/api/ruser/'.$randomInt)
        ->assertStatus(200);
    }
    function testDeleteWrongRuserAdmin()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Delete', '/api/ruser/10000000')
        ->assertStatus(403);
    }
    //////////////////////////////////////////////////////////User//////////////////////////////////////////////////////////////////

    function testGetAllPostsUser()
    {
        global $randomInt;
        $randomInt = rand(30, 100000);
        //fwrite(STDERR, print_r($randomInt, TRUE));
        $response = $this->json('POST', '/api/register', [
                'name'=> 'test' . $randomInt,
                'email'=> 'test' . $randomInt . '@gmail.com',
                'password'=> 'test123'
            ])
                ->assertStatus(200);

        global $token;
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'test' . $randomInt . '@gmail.com', 'password'=> 'test123'
        ]);

        //$this->setToken($response->json()['access_token']);
        $token = $response->json()['access_token'];

        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('Get', '/api/rusers')
                ->assertStatus(403);
    }

    function testCreateNewRuserUser()
    {
            global $token;
            global $randomInt;
            global $ruserId;
            $randomInt = rand(30, 100000);

            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
            ->json('POST', '/api/auth/me')->assertOk();
            global $user;
            $user = User::findOrFail($response->json()['id']);
    
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('Post', '/api/connect', [
                'username' => 'ruser' . $randomInt,     
                'password' => 'new',
                'user_id'=>$user->id
            ])
                ->assertStatus(201);   
    }

    function testUpdateWrongRuserUser()
    {
            global $token;
            global $randomInt;
            $randomInt = rand(30, 100000);
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
            ->json('POST', '/api/auth/me')->assertOk();
            global $user;
            $user = User::findOrFail($response->json()['id']);
    
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('put', '/api/ruser', [
                'ruser_id' => 1,
                'username' => 'ruser' . $randomInt. 'new',     
                'password' => 'new',
                'user_id'=>'30'
            ])
                ->assertStatus(403);       
    }

    function testGetOneWrongRuserUser()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/ruser/1')
        ->assertStatus(403);
    }
    function testGetOneWrongRuserPostsUser()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/ruserposts/1')
        ->assertStatus(403);
    }
    function testDeleteWrongRuserUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Delete', '/api/ruser/1')
        ->assertStatus(403);
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
