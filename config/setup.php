<?PHP
include('database.php');

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

try {
    $sql = 'CREATE DATABASE IF NOT EXISTS camagru_db; USE camagru_db;';
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

try {
    $users = 'CREATE TABLE IF NOT EXISTS `users` (
	uid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(40),
	password VARCHAR(255),
	email VARCHAR(255))';
    $pdo->exec($users);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

try {
    $images = 'CREATE TABLE IF NOT EXISTS `images` (
	iid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	uid int NOT NULL,
	imageHash VARCHAR(18),
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)';
    $pdo->exec($images);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

?>
