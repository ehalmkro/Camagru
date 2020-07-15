<?php

	function db_connect()
	{
		$DB_DSN = 'mysql:dbname=camagru_db;host=127.0.0.1';
		$DB_USER = 'root';
		$DB_PASSWORD = 'password';

		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		}
		catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}

		return ($pdo);
	}



?>
