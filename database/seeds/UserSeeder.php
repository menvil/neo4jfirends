<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder {

    public function run()
    {
        User::clearDatabase();
        $count = 15;
        $friend_join = 1;
        $total = rand(10,$count);

        for($i=1;$i<=$total; $i++){
            User::create(['name' => 'User_'.$i]);
        }

        for($i=1;$i<=$total; $i++) {
            $user = User::where("name", "User_" . $i)->first();
            for($j=1;$j<=$friend_join; $j++) {
                $relation = rand(1, $total);

                if ($i != $relation) {
                    $user1 = User::where("name", "User_" . $relation)->first();
                    $not_firends = true;
                    $friends = $user->friend()->get()->toArray();
                    foreach($friends as $fr){
                        if((int)$fr['id'] === (int)$user1->id)
                            $not_firends = false;
                    }
                    if($not_firends === true) {
                        $user->friend()->attach($user1);
                        $user1->friend()->attach($user);

                    }
                }

            }
        }
    }

}