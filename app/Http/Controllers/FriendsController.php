<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;

class FriendsController extends Controller {

	/**
	 * Display the friends of the User
	 *
	 * @param  int  $id
	 * @param  int	$level
	 * @return Response
	 */
	public function show($id, $level = 1)
	{

		try{

			$friends_by_level = [];
			$names = ["User_" .$id];

			for($i=1; $i<=$level; $i++){
				$cur_level = User::whereIn("name", $names)->get();
				foreach($cur_level as $cur){
					$friends = $cur->friend()->get()->toArray();
					foreach($friends as $friend){
						if(!in_array($friend['name'], $names) && $friend['name'] != "User_" .$id){
							$names[]=$friend['name'];
							$friends_by_level[$i][]=$friend;
						}
					}

				}
			}

			return response()->json($friends_by_level);

		} catch (Exception $e){
			dd($e->getMessage());
		}
	}

	/**
	 * Remove user from friends
	 *
	 * @param  int  $from
	 * @param  int 	$to
	 *
	 * @return Response
	 */
	public function destroy($from, $to)
	{

		$user = User::where("name", "User_".$from)->first();
		$requested_user = User::where("name", "User_".$to)->first();

		if(!$requested_user){
			return response()->json(['error' => 'Incorrect requested user']);
		}

		//Are you friends
		$myRequests = $user->friend()->get()->lists('name');
		if(!in_array('User_'.$to, $myRequests)){
			return response()->json(['error' => 'This user is not your friend']);
		}
		$user->friend()->detach($requested_user);
		$requested_user->friend()->detach($user);
		$user->request()->attach($requested_user);

		return response()->json(['result' => true, 'error'=>false ]);



	}

}
