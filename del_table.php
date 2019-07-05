<?php


$user = 'root';
$pass = 'root';

try {
    $dbh = new PDO('mysql:host=127.0.0.1;port=8889;dbname=Park', $user, $pass);
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql = 'DELETE FROM coord;';
$sql.= 'ALTER TABLE coord AUTO_INCREMENT=1';

$stmt = $dbh->prepare($sql);

$stmt->execute();

$dbh = null;

echo "Deleted table";
