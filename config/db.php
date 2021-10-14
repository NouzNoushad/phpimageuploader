<?php

$conn = mysqli_connect('localhost', 'root', '', 'phplogin');

if(!$conn){
	
	die("Connection error: " . mysqli_connect_error());
}

?>