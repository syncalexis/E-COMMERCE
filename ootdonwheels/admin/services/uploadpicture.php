<?php
	session_start();
	require 'common/database.php';

	class UploadImage {

		public function saveProductImage($productid,$prod_imagepath,$prod_imagefilename) {
			$config = new Config();

			$response = array();

			$conn = $config->conn;

			if($conn->connect_error){
				echo $conn->connect_error;

			} else {
				$query = 'INSERT INTO prod_image(productid,prod_imagepath,prod_imagefilename) VALUES(?,?,?)';
				
				if($stmt = $conn->prepare($query)){
					$stmt->bind_param('sss',$productid,$prod_imagepath,$prod_imagefilename);
					
					if($stmt->execute()){

						$stmt->close();
					} else {
						echo $stmt->error();
					}
				}
				$conn->close();
			}
		}

		public function doUploadImage($filename,$tmpname,$filesize,$productid){

			$app = new UploadImage();

			$response = array();

			$target_dir = "../../public/upload/products/";

			$target_file = $target_dir . basename($filename);

			$uploadOk = 1;
			$errormsg = '';
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			// Check if image file is a actual image or fake image
			$check = getimagesize($tmpname);
			if($check == false) {
			    $uploadOk = 0;
			    $errormsg .= 'Invalid Image file. <br>';
			}
			// Check if file already exists
			if (file_exists($target_file)) {
			    $uploadOk = 0;
			    $errormsg .= 'Filename already exist. <br>';
			}
			// Check file size
			if ($filesize > 2000000) {
			    $uploadOk = 0;
			    $errormsg .= 'File too large (2M max filesize) . <br>';
			}

			if ($uploadOk == 0) {
			    $response['isSuccess'] = false;
				$response['value'] = 0;
				$response['msg'] = $errormsg;
			} else {
			    if (move_uploaded_file($tmpname, $target_file)) {
					
					$app->saveProductImage($productid,$target_file,$filename);

					$response['isSuccess'] = true;
					$response['value'] = basename($filename);
					$response['msg'] = basename($filename). ' Successfully Uploaded';

			    } else {
			        $response['isSuccess'] = false;
					$response['value'] = 0;
					$response['msg'] = 'Error uploading your file';
			    }
			}

			return json_encode($response);
		}
	}

	$app = new UploadImage();

	if(isset($_REQUEST['productid'])) {
		$filename = $_FILES["fileToUpload"]["name"];
		$tmpname = $_FILES["fileToUpload"]["tmp_name"];
		$filesize = $_FILES["fileToUpload"]["size"];
		$productid = $_REQUEST['productid'];

		echo $response = $app->doUploadImage($filename,$tmpname,$filesize,$productid);
	} else {
		echo 'ERROR: No direct access';
	}
	
?>
