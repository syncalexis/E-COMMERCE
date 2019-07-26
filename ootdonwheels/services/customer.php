<?php
	session_start();
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	require 'common/database.php';

	class Customer {
		public function saveCustomer($emailadd,$password,$fullname,$address) {
			$config = new Config();
			
			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'INSERT INTO customer(emailadd,password,fullname,address,lastlogin) VALUES(?,?,?,?,now())';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('ssss',$emailadd,$password,$fullname,$address);
					
					if($stmt->execute()){
						$response['isSuccess'] = true;
						$stmt->close();	
					} else {
						$response['isSuccess'] = false;
						$response['msg'] = $stmt->error;
					}
				} else {
					$response['isSuccess'] = false;
					$response['msg'] = $conn->error;
				}
				$conn->close();
			}

			return json_encode($response);
		}

		public function loginCustomer($emailadd,$password){
			$config = new Config();
			
			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select count(*) as count from customer where emailadd = ? and password = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('ss',$emailadd,$password);

					if($stmt->execute()){
						$stmt->bind_result($count);
						$stmt->store_result();

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								return $count;
							}
							
						}

						$stmt->close();	
					} 
				} 
				$conn->close();
			}

		}

		public function checkIfUserExist($emailadd){
			$config = new Config();
			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select count(*) as count from customer where emailadd = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$emailadd);
					if($stmt->execute()){
						$stmt->bind_result($count);
						$stmt->store_result();
						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								return $count;
							}
						}

						$stmt->close();	
					}
				}
				$conn->close();
			}
		}

	}


	$app = new Customer();

	if(isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	} else {
		$action = '';
	}
	
	switch($action) {
		case 'saveCustomer':
			$emailadd = $_REQUEST['emailadd'];
			$password = $_REQUEST['password'];
			$fullname = $_REQUEST['fullname'];
			$address = $_REQUEST['address'];

			if($app->checkIfUserExist($emailadd) > 0) {
				echo ' {"isSuccess": false}';
			} else {
				echo $app->saveCustomer($emailadd,$password,$fullname,$address);
				$_SESSION['activeuser'] = $emailadd;
			}
			
			break;

		case 'loginCustomer':
			$emailadd = $_REQUEST['emailadd'];
			$password = $_REQUEST['password'];
			echo $app->loginCustomer($emailadd,$password);

			if($app->loginCustomer($emailadd,$password) > 0) {
				$_SESSION['activeuser'] = $emailadd;
			}
			break;

		default:
			echo 'ERROR: No direct access';
			break;
	}
	
?>