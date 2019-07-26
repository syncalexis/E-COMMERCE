<?php
	session_start();
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	require 'common/database.php';

	class Product {

		public function getProductperCategory($categoriesid){
			$config = new Config();
			$app = new Product();

			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select productid,categoriesid,productname,onhand_qty,unit_price,productdesc,date_created from product where categoriesid = ? order by productname';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('i',$categoriesid);

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
								$result['unit_price'] = number_format($unit_price,2);
								$result['productdesc'] = $productdesc;
								if($app->getProdImage($productid) == '0') {
									$result['image'] = 'no-image.png';
								} else {
									$result['image'] = $app->getProdImage($productid);		
								}
								
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

		public function updateQty($tmp_cartid,$qty,$cartsession) {
			$config = new Config();
			$app = new Product();

			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'UPDATE tmp_cart set qty = ? where tmp_cartid = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('ii',$qty,$tmp_cartid);
					
					if($stmt->execute()){
						$response['isSuccess'] = true;
						$response['newtotal'] = $app->getTotalAmounCart($cartsession);
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

		public function getTotalAmounCart($cartsession){
			$config = new Config();
			
			$response = array();
			$result = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select sum((unit_price * order_qty)) as total from tmp_cart_details where cartsession = ? group by cartsession;';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$cartsession);
					if($stmt->execute()){
						$stmt->bind_result($total);
						$stmt->store_result();

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								return $total;
							}
						}

						$stmt->close();	
					}
				}
				$conn->close();
			}
		}

		public function removeCartList($tmp_cartid) {
			$config = new Config();
			
			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'DELETE from tmp_cart where tmp_cartid = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('i',$tmp_cartid);
					
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

		public function savePayment($customerid,$cardnumber,$securitycode,$nameoncard,$expirationmonth,$expirationyear,$cartsession) {
			$config = new Config();
			$app = new Product();
			
			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'INSERT INTO payment(customerid,cardnumber,securitycode,nameoncard,expirationmonth,expirationyear) VALUES (?,?,?,?,?,?)';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('ssisss',$customerid,$cardnumber,$securitycode,$nameoncard,$expirationmonth,$expirationyear);
					
					if($stmt->execute()){
						$app->saveCart($cartsession,$customerid);
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

		public function getCartid($cartsession){
			$config = new Config();
			
			$response = array();
			$result = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'SELECT cartid from cart where cartsession = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$cartsession);
					if($stmt->execute()){
						$stmt->bind_result($cartid);
						$stmt->store_result();

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								return $cartid;
							}
						}

						$stmt->close();	
					}
				}
				$conn->close();
			}
		}

		public function saveCart($cartsession,$customerid) {
			$config = new Config();
			$app = new Product();

			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'INSERT INTO cart(cartsession,customerid) VALUES (?,?)';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('ss',$cartsession,$customerid);
					
					if($stmt->execute()){
						unset($_SESSION['cart']);
						$app->getCartSession($cartsession);
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

			//return json_encode($response);
		}

		public function getCartSession($cartsession){
			$config = new Config();
			$app = new Product();
			
			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select cartsession,productid,order_qty from tmp_cart_details where cartsession = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$cartsession);

					if($stmt->execute()){
						$stmt->bind_result($cartsession,$productid,$order_qty);
						$stmt->store_result();

						if($stmt->num_rows > 0){
							$cartid = $app->getCartid($cartsession);
							while($stmt->fetch()){
								$app->saveCartList($cartid,$productid,$order_qty);
							}
							
						}

						$stmt->close();	
					} 
				} 
				$conn->close();
			}

		}

		public function saveCartList($cartid,$productid,$order_qty) {
			$config = new Config();
			$app = new Product();

			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'INSERT INTO cartlist(cartid,productid,order_qty) VALUES (?,?,?)';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('iii',$cartid,$productid,$order_qty);
					
					if($stmt->execute()){
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

			//return json_encode($response);
		}

		public function getProdImage($productid){
			$config = new Config();
			
			$response = array();
			$result = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select prod_imagefilename from prod_image where productid = ? group by date_created desc limit 1';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$productid);
					if($stmt->execute()){
						$stmt->bind_result($prod_imagefilename);
						$stmt->store_result();

						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								return $prod_imagefilename;
							}
						} else {
							return 0;
						}

						$stmt->close();	
					}
				}
				$conn->close();
			}
		}
	}


	$app = new Product();

	if(isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	} else {
		$action = '';
	}
	
	switch($action) {
		case 'getProductperCategory':
			$categoriesid = $_REQUEST['categoriesid'];
			echo $app->getProductperCategory($categoriesid);
			break;

		case 'updateQty':
			$cartsession = $_REQUEST['cartsession'];
			$tmp_cartid = $_REQUEST['tmp_cartid'];
			$qty = $_REQUEST['qty'];

			echo $app->updateQty($tmp_cartid,$qty,$cartsession);
			break;

		case 'removeCartList':
			$tmp_cartid = $_REQUEST['tmp_cartid'];
			echo $app->removeCartList($tmp_cartid);
			break;

		case 'savePayment':
			$emailadd = $_SESSION['activeuser'];
			$customerid = $app->getCustomerId($emailadd);
			$cardnumber = $_REQUEST['cardnumber'];
			$securitycode = $_REQUEST['securitycode'];
			$nameoncard = $_REQUEST['nameoncard'];
			$expirationmonth = $_REQUEST['expirationmonth'];
			$expirationyear = $_REQUEST['expirationyear'];
			$cartsession = $_SESSION['cart'];
			echo $app->savePayment($customerid,$cardnumber,$securitycode,$nameoncard,$expirationmonth,$expirationyear,$cartsession);
			break;
		case 'getProdImage':
			$productid = $_REQUEST['productid'];
			echo $app->getProdImage($productid);
			break;
		default:
			echo 'ERROR: No direct access';
			break;
	}
	
?>