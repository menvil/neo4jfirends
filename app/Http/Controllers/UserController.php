<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Debug\Dumper;

use Illuminate\Http\Request;
use Log, DB;

class UserController extends Controller {

	/**
	 * Display user info
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){

		try{

			$user = User::where("name", "User_" .$id)->first();
			return response()->json($user);

		} catch (Exception $e){
			dd($e->getMessage());
		}

	}





}
