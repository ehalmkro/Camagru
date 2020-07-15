<?
	include('database.php');

	try{	
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}	catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	exit(1);
}	

	$sql = 'CREATE DATABASE IF NOT EXISTS camagru_db; USE camagru_db;';
	$pdo->exec($sql);
	
	$users = 'CREATE TABLE IF NOT EXISTS `users` (
	uid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(40),
	password VARCHAR(255),
	email VARCHAR(255))';

	
	$pdo->exec($users);
?>
