<?php
	session_start();
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	require 'common/database.php';

	class Order {

		public function getOrder($customerid){
			$config = new Config();
			
			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select cartid, cartsession, customerid, date_created from cart where customerid = ? order by date_created desc';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('i',$customerid);

					if($stmt->execute()){
						$stmt->bind_result($cartid, $cartsession, $customerid, $date_created);
						$stmt->store_result();
						$response['isSuccess'] = true;

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								$result['formattedcartid'] = str_pad($cartid,8,0,STR_PAD_LEFT);
								$result['cartid'] = $cartid;
								$result['cartsession'] = $cartsession;
								$result['customerid'] = $customerid;
								$result['date_ordered'] = $date_created;

								$data[] = $result;
							}
							$response['orderlist'] = $data;
						} else {

							$response['msg'] = 'No Result Found';
						}

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

		public function getCustomerId($emailadd){
			$config = new Config();
			
			$response = array();
			$result = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'SELECT customerid from customer where emailadd = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$emailadd);
					if($stmt->execute()){
						$stmt->bind_result($customerid);
						$stmt->store_result();

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								return $customerid;
							}
						}

						$stmt->close();	
					}
				}
				$conn->close();
			}
		}

	}


	$app = new Order();

	if(isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	} else {
		$action = '';
	}
	
	switch($action) {
		case 'getOrder':
			$emailadd = $_SESSION['activeuser'];
			$customerid = $app->getCustomerId($emailadd);
			echo $app->getOrder($customerid);
			break;
		default:
			echo 'ERROR: No direct access';
			break;
	}
	
?>