<?php


function read_db(){
	$db = file_get_contents("db.json",true);
	return $db;
}

function save_db($json){
	file_put_contents("db.json",json_encode($json,JSON_PRETTY_PRINT));
}

function update_rotine($array){
	$db = read_db()["rotine"];
	foreach(array_keys($array) as $rotine){
		$db[$rotine] = $array[$rotine];
	}
	save_db($db);
}
		
