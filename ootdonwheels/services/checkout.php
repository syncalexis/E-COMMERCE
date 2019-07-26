<?php
	session_start();

	require 'common/database.php';

	class Checkout {
		
		public function getCartSession($cartsession){
			$config = new Config();
			
			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select tmp_cartid,cartsession,productid,productname,productdesc,unit_price,order_qty,(order_qty*unit_price) as total_amount,date_created from tmp_cart_details where cartsession = ? order by date_created desc';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$cartsession);

					if($stmt->execute()){
						$stmt->bind_result($tmp_cartid,$cartsession,$productid,$productname,$productdesc,$unit_price,$order_qty,$total_amount,$date_created);
						$stmt->store_result();
						

						if($stmt->num_rows > 0){
							$response['isSuccess'] = true;
							while($stmt->fetch()){
								$result['tmp_cartid'] = $tmp_cartid;
								$result['cartsession'] = $cartsession;
								$result['productid'] = $productid;
								$result['productname'] = $productname;
								$result['productdesc'] = $productdesc;
								$result['unit_price'] = $unit_price;
								$result['formattedunit_price'] = number_format($unit_price,2);
								$result['order_qty'] = $order_qty;
								$result['total_amount'] = $total_amount;
								$result['formattedtotal_amount'] = number_format($total_amount,2);
								$result['date_created'] = $date_created;

								$data[] = $result;
							}
							$response['cartlist'] = $data;
						} else {
							$response['isSuccess'] = false;
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

	$app = new Checkout();

	/*if(isset($_SESSION['cart'])) {
		echo $app->getCartSession($_SESSION['cart']);
	} else if(isset($_REQUEST['cart'])) {
		echo $app->getCartSession($_REQUEST['cart']);
	} else {
		echo ' {"isSuccess": false}';
	}*/

	if(isset($_REQUEST['cart'])) {
		echo $app->getCartSession($_REQUEST['cart']);
	} else {

		if(isset($_SESSION['cart'])) {
			echo $app->getCartSession($_SESSION['cart']);
		} else{
			echo ' {"isSuccess": false}';
		}
	}
?>