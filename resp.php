<?php

require_once 'db_config.php';

// $user = 'root';
// $pass = 'root';
// // $name = 'assasas';
//
// try {
//     $dbh = new PDO('mysql:host=127.0.0.1;port=8889;dbname=Park', $user, $pass);

    foreach ($dbh->query("SELECT AsText(polygonx) from coord WHERE polygonx IS NOT NULL ") as $row) {
        // print_r($row);
        $ini[] = $row[0];
    }

    foreach ($dbh->query("SELECT AsText(multipol) from coord WHERE multipol IS NOT NULL") as $row3) {
        $ini2[] = $row3[0];
    }

    $stmt = $dbh->prepare("SELECT ST_AsGeoJSON(ST_GeomFromText(:ini));");

    foreach ($ini as $inp) {
        $stmt->execute(['ini' => $inp]);

        while ($row2 = $stmt->fetch()) {

            $out[] = '{
          "type": "Feature",
          "geometry": ' . $row2[0] . ', "properties": {
          }}' ;
        }
    }

    foreach ($ini2 as $inp2) {
        $stmt->execute(['ini' => $inp2]);

        while ($row3 = $stmt->fetch()) {

            $out2[] = '{
          "type": "Feature",
          "geometry": ' . $row3[0] . ', "properties": {
          }}' ;
        }
    }
// } catch (PDOException $e) {
//     echo "Error!: " . $e->getMessage() . "<br/>";
//     die();
// }

  // $dbh = null;

  $data = '{
    "type": "FeatureCollection",
    "features": [';

  foreach ($out as $feature) {
      $data = $data . $feature . ',';
  }
  foreach ($out2 as $feature) {
      $data = $data . $feature . ',';
  }
  $data = rtrim($data, ', ');
  $data = $data . '] }';

  // print_r($data);

$resp = $data;

$registration = $_POST['registration'];
$name= $_POST['name'];
$email= $_POST['email'];

if ($registration == "success"){
 // some action goes here under php
 echo json_encode(array("abc"=>$resp));
}
