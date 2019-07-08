<?php

require_once 'db_config.php';

$registration = $_POST['registration'];
// $zones= $_POST['zones'];
$zones = json_decode($_POST['zones']);
$spots = json_decode($_POST['spots']);


$i = 0;
foreach ($zones as $zone) {
    $i++;
    $sql = "UPDATE coord SET zone=?, spots=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$zone, $spots[$i-1], $i]);
}

// $resp = $zones[0];


if ($registration == "success") {
    // some action goes here under php
    // echo json_encode(array("abc"=>$resp));
    echo "Zones updated with default settings";
}
