<?php

require_once 'db_config.php';

$zone = $_POST['choice'];
// $zones= $_POST['zones'];
$spots = $_POST['spots'];

$id = $_POST['id'];

// if ($spots == ""){
// echo "asdcasdvcasdvcervaasd";
// }
// echo $zone;

if (($spots !== "") && ($zone !== 'default')) {
  $sql = "UPDATE coord SET zone=?, spots=? WHERE id=?";
  $stmt = $dbh->prepare($sql);
  if ($stmt->execute([$zone, $spots, $id])) {
    echo "Zone and spots updated for polygon with ID: " . $id;
  }
}

else if (($spots == "") && ($zone == 'default')) {
   echo "No change made for polygon with ID: " . $id;
}

else {
  if (($spots == "") && ($zone !== 'default')) {
    $sql = "UPDATE coord SET zone=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    if ($stmt->execute([$zone, $id])) {
      echo "Zone updated for polygon with ID: " . $id;
    }
  }
  else if (($spots !== "") && ($zone == 'default')) {
    $sql = "UPDATE coord SET spots=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    if ($stmt->execute([$spots, $id])) {
      echo "Spots updated for polygon with ID: " . $id;
    }
  }
}

// $sql = "UPDATE coord SET zone=?, spots=? WHERE id=?";
// $stmt = $dbh->prepare($sql);
// $stmt->execute([$zone, $spots, $id]);



    // some action goes here under php
    // echo json_encode(array("abc"=>$resp));
    // echo $choice;
