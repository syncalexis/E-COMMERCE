<?php 
include 'connection.php';?>
<?php
if(isset($_POST['login']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	if (empty($username) || empty($password))
	{
		$msg='Required fields';
		header('Location:index.html?msg='.$msg.'');
	}
	else{
		$query = mysqli_query($mysqli_conn,"SELECT * FROM `admin` WHERE `username` = '$username' and `password` = '$password'");
		$count = mysqli_num_rows($query);
		if($count == 1){
			session_start();
			$row=mysqli_fetch_array($query);
		
			header('Location:admin.html');
		}
		else{
			$msg=('Login Error');
			header('Location:index.html?msg='.$msg.'');
		}
	}
}
