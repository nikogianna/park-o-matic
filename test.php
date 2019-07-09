<?php

require_once 'db_config.php';

$choice = $_POST['choice'];
// $zones= $_POST['zones'];
$spots = $_POST['spots'];

$id = $_POST['id'];

// if ($spots == ""){
// echo "asdcasdvcasdvcervaasd";
// }

if (($spots !== "") && ($choice !== 'default')) {echo "Nothing";}

else if (($spots == "") && ($choice == 'default')) {echo "Everything empty";}

else {
  if (($spots == "") && ($choice !== 'default')) {echo "Only choice";}
  else if (($spots !== "") && ($choice == 'default')) {echo "Only num";}
}

// $sql = "UPDATE coord SET zone=?, spots=? WHERE id=?";
// $stmt = $dbh->prepare($sql);
// $stmt->execute([$zone, $spots, $id]);



    // some action goes here under php
    // echo json_encode(array("abc"=>$resp));
    // echo $choice;
