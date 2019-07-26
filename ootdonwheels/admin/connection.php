<?php
$host='localhost';
$name='root';
$password='';
$database='cafenpastry';

if(mysqli_connect($host,$name,$password))
{
$mysqli_conn=mysqli_connect($host,$name,$password);
if (mysqli_select_db($mysqli_conn,$database))
{
}
else{
	die('could not find Database:');
	}
}
else{
	die('could not connect.');
}
	?>