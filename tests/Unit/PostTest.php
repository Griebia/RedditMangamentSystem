<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Ruser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    //Admin
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
                ->json('Get', '/api/posts')
                ->assertStatus(200);
    }

    function testCreateNewPostAdmin()
    {
            global $token;
            global $randomInt;
            $randomInt = rand(30, 100000);
    
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('Post', '/api/post', [
                'post_id' => '3',     
                'subreddit' => 'new',
                'url'=> 'dankememes,memes',
                'title'=> 'test' . $randomInt,
                'sr' => 'new',
                'kind'=>'new',
                'postTime'=>'1',
                'ruser_id'=>'1'
            ])
                ->assertStatus(201);
            
    }

    function testGetOnePostAdmin()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/post/2')
        ->assertStatus(200);
    }
    function testGetOnePostWrongAdmin()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/post/100000000000000')
        ->assertStatus(403);
    }

    

    function testUpadatePostWrongAdmin()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
            ->json('Put', '/api/post', [
            'post_id' => '1000000000000000000', 
            'subreddit' => 'new',
            'url'=> 'dankememes,memes',
            'title'=> 'testNew' . $randomInt,
            'sr' => 'new',
            'kind'=>'new',
            'postTime'=>'1',
            'ruser_id'=>'1'
        ])
            ->assertStatus(403);
    }

    function testUpadatePostAdmin()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
            ->json('Put', '/api/post', [
            'post_id' => '3', 
            'subreddit' => 'new',
            'url'=> 'dankememes,memes',
            'title'=> 'testNew' . $randomInt,
            'sr' => 'new',
            'kind'=>'new',
            'postTime'=>'1',
            'ruser_id'=>'1'
        ])
            ->assertStatus(200);
    }
    function testDeleteNewPostAdmin()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Delete', '/api/post/3')
        ->assertStatus(200);
    }

    //User
    //Done
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
                ->json('Get', '/api/posts')
                ->assertStatus(403);
    }
    //
    function testCreateNewPostUser()
    {
            global $token;
            global $randomInt;
            $randomInt = rand(10000, 100000);
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
            ->json('POST', '/api/auth/me')->assertOk();
            global $user;
            $user = User::findOrFail($response->json()['id']);
    
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('Post', '/api/connect', [
                'ruser_id' => $randomInt,
                'username' => 'admins',     
                'password' => 'new',
                'user_id'=>$user->id
            ]);
            
            
            $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
                ->json('Post', '/api/post', [
                'post_id' => '10000',     
                'subreddit' => 'new',
                'url'=> 'dankememes,memes',
                'title'=> 'test' . $randomInt,
                'sr' => 'new',
                'kind'=>'new',
                'postTime'=>'1',
                'ruser_id'=>$randomInt
            ])
                ->assertStatus(201);
            
    }

    function testGetOnePostUser()
    {
        global $token;
        //fwrite(STDERR, print_r($token, TRUE));
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/post/10000')
        ->assertStatus(200);
    }
    
    function testGetOnePostWrongUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Get', '/api/post/2')
        ->assertStatus(403);
    }
    

    function testUpadatePostUser()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
            ->json('Put', '/api/post', [
            'post_id' => '10000', 
            'subreddit' => 'new',
            'url'=> 'dankememes,memes',
            'title'=> 'testNew' . $randomInt,
            'sr' => 'new',
            'kind'=>'new',
            'postTime'=>'1',
            'ruser_id'=>$randomInt
        ])
            ->assertStatus(200);
    }

    function testUpadatePostWrongUser()
    {
        global $token;
        global $randomInt;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
            ->json('Put', '/api/post', [
            'post_id' => '2', 
            'subreddit' => 'new',
            'url'=> 'dankememes,memes',
            'title'=> 'testNew' . $randomInt,
            'sr' => 'new',
            'kind'=>'new',
            'postTime'=>'1',
            'ruser_id'=>'1'
        ])
            ->assertStatus(403);
    }

    function testDeleteNewPostUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Delete', '/api/post/10000')
        ->assertStatus(200);
    }

    function testDeleteWrongPostUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Delete', '/api/post/10000')
        ->assertStatus(403);
    }
    function testDeleteExistingWrongPostUser()
    {
        global $token;
        $response = $this->withHeaders(['HTTP_Authorization' => 'Bearer ' . $token])
        ->json('Delete', '/api/post/2')
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
