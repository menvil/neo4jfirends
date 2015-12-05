<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;

class RequestsController extends Controller {

	/**
	 * Display friend Request to other users
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		try{

			$user = User::where("name", "User_".$id)->first();
			return response()->json($user->outbound()->get()->toArray());

		} catch (Exception $e){
			dd($e->getMessage());
		}
	}


	/**
	 * Display friend Request to this user (incoming friend request)
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function income($id)
	{
		try{

			$user = User::where("name", "User_".$id)->first();
			return response()->json($user->request()->get()->toArray());

		} catch (Exception $e){
			dd($e->getMessage());
		}
	}


	/**
	 * Send friend request to user
	 *
	 * @return Response
	 */
	public function create($from, $to)
	{

		$user = User::where("name", "User_".$from)->first();
		$requested_user = User::where("name", "User_".$to)->first();

		if(!$requested_user){
			return response()->json(['error' => 'Incorrect requested user']);
		}

		if($from == $to){
			return response()->json(['error' => "You can't send friend request to yourself"]);
		}

		//Check do we have any friend requests from this user to requester
		$myRequests = $user->outbound()->get()->lists('name');
		if(in_array('User_'.$to, $myRequests)){
			return response()->json(['error' => "You already sent friend request to this user"]);
		}

		//Check maby you are friends already
		$myFriends = $user->friend()->get()->lists('name');
		if(in_array('User_'.$to, $myFriends)){
			return response()->json(['error' => "You already friends with this user"]);
		}

		//Maby you have the income friend request to you ? Than we will make you friends automatically
		$myRequests = $user->request()->get()->lists('name');
		if(in_array('User_'.$to, $myRequests)){
			$user->request()->detach($requested_user);
			$user->friend()->attach($requested_user);
			$requested_user->friend()->attach($user);
			return response()->json(['result' => true, 'error'=>false ]);
		} else {
			$requested_user->request()->attach($user);
			return response()->json(['result' => true, 'error'=>false ]);
		}

	}

	/**
	 * Remove the friend request
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($from, $to)
	{

		$user = User::where("name", "User_".$from)->first();
		$requested_user = User::where("name", "User_".$to)->first();

		if(!$requested_user){
			return response()->json(['error' => 'Incorrect requested user']);
		}

		//Maby you have the income friend request to you ? Than we will make you friends automatically
		$myRequests = $user->outbound()->get()->lists('name');
		if(!in_array('User_'.$to, $myRequests)){
			return response()->json(['result' => true, 'error'=>false ]);
		}
		$user->request()->detach($requested_user);

		return response()->json(['result' => true, 'error'=>false ]);

	}

}
