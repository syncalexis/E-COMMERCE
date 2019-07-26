<?php
	class Config {
		public function __construct(){
			$dbservername = 'localhost';
			$dbusername = 'root';
			$dbpassword = '';
			$dbname = 'cafenpastry';
			
			$conn = new mysqli($dbservername,$dbusername,$dbpassword,$dbname);

			$this->conn = $conn;
		}

	}
?>