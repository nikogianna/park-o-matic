<?php

require_once 'db_config.php';

$registration = $_POST['registration'];
$zones = json_decode($_POST['zones']);
$spots = json_decode($_POST['spots']);
$id = json_decode($_POST['ids']);


$i = -1;
foreach ($zones as $zone) {
    $i++;
    $sql = "UPDATE coord SET zone=?, spots=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$zone, $spots[$i], $id[$i]]);
}

echo "Οι θέσεις στάθμευσης και η ζώνη ανανεώθηκαν";
