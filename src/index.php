<?php

require_once __DIR__.'/../vendor/autoload.php';


use Mille\App\App;


$app = new App(__DIR__);


$app->route($method='GET',$path='/',function($request){
	$params = $request->params;

	$user = $params['user']??false;

	if($user){
    		return "<h1>oi, $user</h1>";
	}else{
		return "<h1>user not found</h1>";

	}
});


$app->run();
