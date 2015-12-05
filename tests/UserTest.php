<?php

class UserTest extends TestCase {

    /**
     * Test user
     *
     * @return void
     */
    public function testGetUser()
    {

        $response = $this->call('GET', Config::get('app.url').'/users/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = json_decode($response->getContent());
        $this->assertEquals("User_1", $user->name);
    }

}
