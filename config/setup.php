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
	username VARCHAR(40) NOT NULL,
	password VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL,
    confirmationCode VARCHAR(255),
    sendNotifications TINYINT(1) DEFAULT 1);';
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

try {
    $comments = 'CREATE TABLE IF NOT EXISTS `comments`(
    cid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    iid int NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    text varchar(600),
    islike BOOLEAN,
    uid int NOT NULL,
    username VARCHAR(40))';
    $pdo->exec($comments);
}   catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}

?>
