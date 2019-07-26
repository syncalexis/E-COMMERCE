<?php
	session_start();
	require 'common/database.php';
	class Cartcounter {

		public function getCartCounter($cartsession){
			$config = new Config();
			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select count(*) as count from tmp_cart where cartsession = ?';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('s',$cartsession);
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

	$app = new Cartcounter();

	if(isset($_SESSION['cart'])) {
		echo $app->getCartCounter($_SESSION['cart']);
	} else {
		echo  0;
	}
?>