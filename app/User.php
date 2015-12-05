<?php namespace App;

use DB;

class User extends \NeoEloquent {

	protected $label = 'User'; // or array('User', 'Fan')

	protected $fillable = ['name', 'email'];

	public function friend()
	{
		return $this->hasMany('App\User', 'FRIEND');
	}

	public function request()
	{
		return $this->belongsToMany('App\User', 'REQUEST');
	}

	public function outbound()
	{
		return $this->hasMany('App\User', 'REQUEST');
	}


	public static function  clearDatabase(){
		DB::statement("MATCH (n) OPTIONAL MATCH (n)-[r]-() DELETE n,r");
	}




}
