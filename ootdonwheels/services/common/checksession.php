<?php
	session_start();

	if(isset($_SESSION['activeuser'])) {
		$arr = array(
			'isSuccess' => true
		);
	} else {
		$arr = array(
			'isSuccess' => false,
			'msg' => 'inactive session'
		);
	}

	echo json_encode($arr);
?>