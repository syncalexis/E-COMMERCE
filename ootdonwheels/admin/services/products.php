<?php
	session_start();
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	require 'common/database.php';

	class Products {
		public function saveProducts($categoriesid,$productname,$onhand_qty,$unit_price,$productdesc) {
			$config = new Config();
			
			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'INSERT INTO product(categoriesid,productname,onhand_qty,unit_price,productdesc) VALUES(?,?,?,?,?)';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('ssids',$categoriesid,$productname,$onhand_qty,$unit_price,$productdesc);
					
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

		public function getCategoriesList(){
			$config = new Config();
			
			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select categoriesid,categorycode,categorydesc,date_created from categories';
				
				if($stmt = $conn->prepare($query)){
					
					if($stmt->execute()){
						$stmt->bind_result($categoriesid,$categorycode,$categorydesc,$date_created);
						$stmt->store_result();
						$response['isSuccess'] = true;

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								$result['categoriesid'] = $categoriesid;
								$result['categorycode'] = $categorycode;
								$result['categorydesc'] = $categorydesc;
								$result['date_created'] = $date_created;

								$data[] = $result;
							}
							$response['categorylist'] = $data;
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

		public function getProductList(){
			$config = new Config();
			
			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select productid,categoriesid,productname,onhand_qty,unit_price,productdesc,date_created from product';
				
				if($stmt = $conn->prepare($query)){
					
					if($stmt->execute()){
						$stmt->bind_result($productid,$categoriesid,$productname,$onhand_qty,$unit_price,$productdesc,$date_created);
						$stmt->store_result();
						$response['isSuccess'] = true;

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								$result['productid'] = $productid;
								$result['categoriesid'] = $categoriesid;
								$result['productname'] = $productname;
								$result['onhand_qty'] = $onhand_qty;
								$result['unit_price'] = $unit_price;
								$result['productdesc'] = $productdesc;
								$result['date_created'] = $date_created;

								$data[] = $result;
							}
							$response['productlist'] = $data;
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

	}


	$app = new Products();

	if(isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	} else {
		$action = '';
	}
	
	switch($action) {
		case 'saveProducts':
			$categoriesid = $_REQUEST['categoriesid'];
			$productname = $_REQUEST['prodname'];
			$onhand_qty = $_REQUEST['stocks'];
			$unit_price = $_REQUEST['price'];
			$productdesc = $_REQUEST['proddesc'];

			echo $app->saveProducts($categoriesid,$productname,$onhand_qty,$unit_price,$productdesc);
			break;

		case 'getCategoriesList':
			echo $app->getCategoriesList();
			break;

		case 'getProductList':
			echo $app->getProductList();
			break;

		default:
			echo 'ERROR: No direct access';
			break;
	}
	
?>