<?php

define('FILE_NAME','work.txt');

$commands = [
	'start'  => [
		'description' => 'starts the work',
		'runs' => 'start_work'
	],
	'stop' => [
		'description' => 'stops the work',
		'runs' => 'stop_work'
	],
	'stats' => [
		'description' => 'show the stats',
		'runs' => 'stats'
	],
	'clear' => [
		'description' => 'clears the current work',
		'runs' => 'clear'
	]
];

if(empty($argv[1])){
    println("No command Specified");
    show_available_commands();
    exit();
}

$command = $argv[1];
$data = [];

if(empty($commands[$command])){
	println("Unsupported Command");
	show_available_commands();
	exit();
}

$commands[$command]['runs']();



function start_work(){
	global $data;
	check_file();
	if(isset($data[0])){
		println("You already started work");
		exit();
	}

	$data[0] = time();
	save_to_file();
}

function stop_work(){
	global $data;
	check_file();
	if(empty($data[0])){
		println("You didn't start work yet");
		exit();
	}
	if(isset($data[1])){
		println("You have already stopped work");
		exit();
	}
	$data[1] = time();
	save_to_file();
}

function stats(){
	global $data;
	check_file();
	if(empty($data[0])){
		println("You didn't start work yet");
		exit();
	}
	if(empty($data[1])){
		println("You started work but didn't stop yet");
		$workTime = time() - $data[0];
		println("You worked untill now for {$workTime} secs");
		exit();
	}
	$workTime = $data[1] - $data[0];
	println("You worked {$workTime} secs");
}

function clear(){
	file_put_contents(FILE_NAME,null);
}

function check_file(){
	global $data;
	if(file_exists(FILE_NAME)){
		$text = trim(file_get_contents(FILE_NAME));
		if(empty($text)){
			$data = [];
		}else{
			$data = explode("\n",$text);
		}
	}else{
		file_put_contents(FILE_NAME,null);
		$data = [];
	}
}

function save_to_file(){
	global $data;
	$text = implode("\n",$data);
	file_put_contents(FILE_NAME,$text);
}

function println($s){
	echo "{$s}\n";
}

function show_available_commands(){
	global $commands;
	foreach($commands as  $commandName => $value){
		println($commandName . ": " . $value['description']);
	}
}