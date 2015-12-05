<?php

class RequestsTest extends TestCase {

    public function testMyRequests()
    {

        $response = $this->call('GET', Config::get('app.url').'/users/2/requests/my');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_11','User_10']);

    }

    public function testMyRequestsNoRequests(){
        $response = $this->call('GET', Config::get('app.url').'/users/10/requests/my');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, []);

    }

    public function testRequestsToMeNoRequests()
    {

        $response = $this->call('GET', Config::get('app.url').'/users/2/requests/me');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, []);

    }

    public function testRequestsToMe()
    {

        $response = $this->call('GET', Config::get('app.url').'/users/10/requests/me');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_2']);

    }

    public function testSendFriendRequestToMe()
    {

        $response = $this->call('PUT', Config::get('app.url').'/users/10/requests/10');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertArrayHasKey('error',$user);
        $this->assertEquals("You can't send friend request to yourself",$user['error']);
    }

    public function testSendFriendRequestThatExists()
    {

        $response = $this->call('PUT', Config::get('app.url').'/users/2/requests/10');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertArrayHasKey('error',$user);
        $this->assertEquals("You already sent friend request to this user",$user['error']);
    }

    public function testSendFriendRequestToFriend()
    {

        $response = $this->call('PUT', Config::get('app.url').'/users/10/requests/3');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertArrayHasKey('error',$user);
        $this->assertEquals("You already friends with this user",$user['error']);
    }

    public function testSendFriendRequestNew()
    {

        $response = $this->call('PUT', Config::get('app.url').'/users/2/requests/9');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertArrayHasKey('error',$user);
        $this->assertArrayHasKey('result',$user);
        $this->assertEquals($response->getContent(), json_encode(['result' => true, 'error'=>false ]));

        //Check then
        $response = $this->call('GET', Config::get('app.url').'/users/2/requests/my');
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_11','User_10','User_9']);

        //Check then
        $response = $this->call('GET', Config::get('app.url').'/users/9/requests/me');
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_2']);

    }

    public function testAcceptFriendRequest(){

        $this->call('PUT', Config::get('app.url').'/users/2/requests/9');
        $response = $this->call('PUT', Config::get('app.url').'/users/9/requests/2');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertArrayHasKey('error',$user);
        $this->assertArrayHasKey('result',$user);
        $this->assertEquals($response->getContent(), json_encode(['result' => true, 'error'=>false ]));

        $response = $this->call('GET', Config::get('app.url').'/users/9/friends');
        $user = json_decode($response->getContent());
        $this->assertEquals(1, count((array)$user));
        $friends = [];
        foreach($user->{1} as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_10', 'User_2']);

    }

    public function testDeleteFriendRequestWhenNoFriendRequest(){

        $response = $this->call('DELETE', Config::get('app.url').'/users/2/requests/7');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertArrayHasKey('error',$user);
        $this->assertArrayHasKey('result',$user);
        $this->assertEquals($response->getContent(), json_encode(['result' => true, 'error'=>false ]));


    }

    public function testDeleteFriendRequestWhenExists(){

        $response = $this->call('DELETE', Config::get('app.url').'/users/2/requests/10');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $user = (array)json_decode($response->getContent());
        $this->assertArrayHasKey('error',$user);
        $this->assertArrayHasKey('result',$user);
        $this->assertEquals($response->getContent(), json_encode(['result' => true, 'error'=>false ]));

        //Check then
        $response = $this->call('GET', Config::get('app.url').'/users/2/requests/my');
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, ['User_11']);

        //Check then
        $response = $this->call('GET', Config::get('app.url').'/users/10/requests/me');
        $user = json_decode($response->getContent());
        $friends = [];
        foreach($user as $us){
            $friends[] = $us->name;
        }
        $this->assertEquals($friends, []);

    }


}
