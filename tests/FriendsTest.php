<?php

class FriendsTest extends TestCase {

    /**
     * Test get friends
     *
     * @return void
     */
    public function testGetFriendsFirstLevel()
    {

        $response = $this->call('GET', Config::get('app.url').'/users/1/friends');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = json_decode($response->getContent());
        $this->assertEquals(1, count((array)$user));
        $friends = [];
        foreach($user->{1} as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_4','User_2']);
    }


    /**
     * Test get friends
     *
     * @return void
     */
    public function testGetFriendsManyLevels()
    {

        $response = $this->call('GET', Config::get('app.url').'/users/1/friends/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = json_decode($response->getContent());
        $this->assertEquals(1, count((array)$user));
        $friends = [];
        foreach($user->{1} as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_4','User_2']);

        $response = $this->call('GET', Config::get('app.url').'/users/4/friends/3');
        $levels = json_decode($response->getContent());
        $this->assertEquals(3, count((array)$levels));
        $friends = [];
        foreach($levels as $key=>$level){

            foreach($level as $us){
                $friends[$key][] = $us->name;
            }
        }

        $this->assertEquals($friends, ['1'=>['User_1','User_8','User_5','User_6'],'2'=>['User_2','User_10'],'3'=>['User_3','User_9']]);

        $response = $this->call('GET', Config::get('app.url').'/users/4/friends/10');
        $levels = json_decode($response->getContent());
        $this->assertEquals(5, count((array)$levels));
        $friends = [];

        foreach($levels as $key=>$level){

            foreach($level as $us){
                $friends[$key][] = $us->name;
            }
        }

        $this->assertEquals($friends, [ '1'=>['User_1','User_8','User_5','User_6'],
                                        '2'=>['User_2','User_10'],
                                        '3'=>['User_3','User_9'],
                                        '4'=>['User_11'],
                                        '5'=>['User_7'],
        ]);

    }

    public function testRemoveFromFriendsDoesNotExists(){

        $response = $this->call('DELETE', Config::get('app.url').'/users/1/friends/1453');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertEquals("Incorrect requested user",$user['error']);

    }


    public function testRemoveFromFriendsWhenNotFriends(){

        $response = $this->call('DELETE', Config::get('app.url').'/users/1/friends/5');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertEquals("This user is not your friend",$user['error']);

    }

    public function testRemoveFromFriends(){

        $response = $this->call('DELETE', Config::get('app.url').'/users/9/friends/10');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals($response->getContent(), json_encode(['result' => true, 'error'=>false ]));

        $response = $this->call('GET', Config::get('app.url').'/users/9/friends');
        $user = json_decode($response->getContent());
        $this->assertEquals(0, count((array)$user));

        //Check then
        $response = $this->call('GET', Config::get('app.url').'/users/10/requests/my');
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_9']);

        //Check then
        $response = $this->call('GET', Config::get('app.url').'/users/9/requests/me');
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_10']);

    }


}
