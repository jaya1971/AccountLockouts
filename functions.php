<?php

// Datbase connection.
function db_conn() {
	
	static $connection;
	
	$connection = mysqli_connect('IP Address','SQLAccount','Password','Database');

	if($connection === false) {
			return mysqli_connect_error(); 
		}
		return $connection;
}

// Write to Log.
function write_log($connection,$date,$user,$event) {
	
	$result = mysqli_query($connection,"INSERT INTO Logs (date,user,event) VALUES ('$date','$user','$event')");

	if($result === false) {
		return mysqli_error($connection);
	} 
}
?>