<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    function testCreateGroup()
    {
        global $randomInt;
        $randomInt = rand(30, 100000);

        $response = $this->json('POST', '/api/group', [
            'group_id'=> 1,
            'info'=> 'dankememes,memes',
            'title'=> 'test' . $randomInt
        ])
            ->assertStatus(201);
    }

    function testCreateWrongGroup()
    {
        global $randomInt;
        $randomInt = rand(30, 100000);

        $response = $this->json('POST', '/api/group', [
            'group_id'=> 'asdasdasd',
            'info'=> 'dankememes,memes',
            'title'=> 'test' . $randomInt
        ])
            ->assertStatus(400);
    }

    function testGetOneGroup()
    {
        $response = $this->json('Get', '/api/group/1')
            ->assertStatus(200);
    }

    function testGetGroupReddit()
    {
        $response = $this->json('Get', '/api/groupreddit/1')
            ->assertStatus(200);
    }

    function testGetOneGroupWrong()
    {
        $response = $this->json('Get', '/api/group/10000')
            ->assertStatus(404);
    }

    function testGetGroups()
    {
        $response = $this->json('Get', '/api/groups')
        ->assertStatus(200);
    }
    
    function testUpdateGroup()
    {
        global $randomInt;
        $randomInt = rand(30, 100000);

        $response = $this->json('put', '/api/group', [
            'group_id'=> 1,
            'info'=> 'dankememes,memes',
            'title'=> 'test' . $randomInt
        ])
            ->assertStatus(200);
    }
    
    function testDeleteGroup()
    {
        $response = $this->json('delete', '/api/group/1')
            ->assertStatus(200);
    }

    function testDeleteWrongGroup()
    {
        $response = $this->json('delete', '/api/group/10000000000')
            ->assertStatus(404);
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
