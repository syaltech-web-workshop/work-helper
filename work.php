<?php

$commands = [
	'start'  => [
		'description' => 'starts the work',
		'runs' => 'startWork'
	],
	'stop' => [
		'description' => 'stops the work',
		'runs' => 'stopWork'
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
    showAvailableCommands();
    exit();
}

$command = $argv[1];
$data = [];

if(empty($commands[$command])){
	println("Unsupported Command");
	showAvailableCommands();
	exit();
}

$commands[$command]['runs']();



function startWork(){
	global $data;
	checkFile();
	if(isset($data[0])){
		println("You already started work");
		exit();
	}

	$data[0] = time();
	saveToFile();
}

function stopWork(){
	global $data;
	checkFile();
	if(empty($data[0])){
		println("You didn't start work yet");
		exit();
	}
	if(isset($data[1])){
		println("You have already stopped work");
		exit();
	}
	$data[1] = time();
	saveToFile();
}

function stats(){
	global $data;
	checkFile();
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
	file_put_contents('work.txt',null);
}

function checkFile(){
	global $data;
	if(file_exists('work.txt')){
		$text = trim(file_get_contents('work.txt'));
		if(empty($text)){
			$data = [];
		}else{
			$data = explode("\n",$text);
		}
	}else{
		file_put_contents('work.txt',null);
		$data = [];
	}
}

function saveToFile(){
	global $data;
	$text = implode("\n",$data);
	file_put_contents('work.txt',$text);
}

function println($s){
	echo "{$s}\n";
}

function showAvailableCommands(){
	global $commands;
	foreach($commands as  $commandName => $value){
		println($commandName . ": " . $value['description']);
	}
}
