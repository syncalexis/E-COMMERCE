<?php
	session_start();
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	require 'common/database.php';

	class Category {

		public function getCategoryList(){
			$config = new Config();
			
			$response = array();
			$result = array();
			$data = array();

			$conn = $config->conn;

			if($conn->connect_error){
				$response['isSuccess'] = false;
				$response['msg'] = $conn->connect_error;

			} else {
				$query = 'select categoriesid,categorycode,categorydesc,date_created from categories order by categorycode';
				
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
	}


	$app = new Category();

	if(isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	} else {
		$action = '';
	}
	
	switch($action) {
		case 'getcategory':
			echo $app->getCategoryList();
			break;
		default:
			echo 'ERROR: No direct access';
			break;
	}
	
?>