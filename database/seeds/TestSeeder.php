<?php

use Illuminate\Database\Seeder;
use App\User;

class TestSeeder extends Seeder
{

    private $friends = [
        'User_1'=>['User_4','User_2'],
        'User_2'=>['User_1'],
        'User_3'=>['User_10','User_11'],
        'User_4'=>['User_1','User_8','User_5','User_6'],
        'User_5'=>['User_4','User_10'],
        'User_6'=>['User_4'],
        'User_7'=>['User_11'],
        'User_8'=>['User_4'],
        'User_9'=>['User_10'],
        'User_10'=>['User_5','User_3','User_9'],
        'User_11'=>['User_3','User_7']
    ];

    private $request = [
        'User_1'=>['User_8'],
        'User_2'=>['User_11','User_10'],
    ];

    public function run()
    {
        User::clearDatabase();
        foreach($this->friends as $key=>$friend){
            User::create(['name' => $key]);
        }
        foreach($this->friends as $key=>$friend){
            $user1 = User::where("name", $key)->first();
            foreach($friend as $fr){
                $user2 = User::where("name", $fr)->first();
                $user1->friend()->attach($user2);
            }
        }

        foreach($this->request as $key=>$request){
            $user1 = User::where("name", $key)->first();
            foreach($request as $fr){
                $user2 = User::where("name", $fr)->first();
                $user2->request()->attach($user1);
            }
        }
    }
}