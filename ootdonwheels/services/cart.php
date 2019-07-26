<?php
	session_start();
	require 'common/database.php';
	class Cart {
		public function saveTmpCart($cardsession,$productid,$qty) {
			$config = new Config();
			
			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'INSERT INTO tmp_cart(cartsession,productid,qty) VALUES(?,?,?)';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('sii',$cardsession,$productid,$qty);
					
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

		public function idProductExist($cardsession,$productid){
			$config = new Config();
			
			$response = array();
			$result = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select count(*) as count from tmp_cart where cartsession = ? and productid = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('si',$cardsession,$productid);
					if($stmt->execute()){
						$stmt->bind_result($count);
						$stmt->store_result();
						$response['isSuccess'] = true;
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


		public function getCurrentQty($cardsession,$productid){
			$config = new Config();
			
			$response = array();
			$result = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select qty from tmp_cart where cartsession = ? and productid = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('si',$cardsession,$productid);
					if($stmt->execute()){
						$stmt->bind_result($qty);
						$stmt->store_result();
						$response['isSuccess'] = true;
						if($stmt->num_rows > 0){

							while($stmt->fetch()){
								return $qty;
							}
						}

						$stmt->close();	
					}
				}
				$conn->close();
			}
		}

		public function updateQty($cardsession,$productid,$qty) {
			$config = new Config();
			
			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'UPDATE tmp_cart set qty = ? where cartsession = ? and productid = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('isi',$qty,$cardsession,$productid);
					
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
		}

	}

	$app = new Cart();

	if(isset($_REQUEST['productid'])) {
		if(!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = md5(uniqid(rand(), true));
		}

		$cardsession = $_SESSION['cart'];
		$productid = $_REQUEST['productid'];
		$qty = $_REQUEST['qty'];
		if($app->idProductExist($cardsession,$productid)> 0) {
			$cqty = $app->getCurrentQty($cardsession,$productid);
			$qty = $cqty + 1;
			$app->updateQty($cardsession,$productid,$qty);
		} else {
			$app->saveTmpCart($cardsession,$productid,$qty);	
		}
		
	}

	//if(isset($_SESSION['cart'])) {
	//	echo $_SESSION['cart'];
	//}
?>