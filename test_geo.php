<?php

$user = 'root';
$pass = 'root';
// $name = 'assasas';

try {
    $dbh = new PDO('mysql:host=127.0.0.1;port=8889;dbname=Park', $user, $pass);

    foreach($dbh->query("SELECT AsText(polygonx) from coord ") as $row) {
        // print_r($row);
        $ini[] = $row[0];
    }

    foreach($dbh->query("SELECT AsText(polygony) from coord WHERE polygony IS NOT NULL") as $row3) {
        // print_r($row);
        $ini2[] = $row3[0];
    }
// print_r($ini[2]);
// $stmt = $dbh->prepare("SELECT ST_AsGeoJSON(ST_GeomFromText('POINT(11.11111 12.22222)'),2);");
$stmt = $dbh->prepare("SELECT ST_AsGeoJSON(ST_GeomFromText(:ini));");


    // // $dbh = "INSERT INTO coord(name) VALUES (:name)";
    // // $stmt= $pdo->prepare($dbh);
    foreach($ini as $inp) {
    $stmt->execute(['ini' => $inp]);

    while ($row2 = $stmt->fetch()) {
        // echo $row2[0];
        $out[] = '{
          "type": "Feature",
          "geometry": ' . $row2[0] . ', "properties": {
          }}' ;
      }    // $dbh = null;
    }

    foreach($ini2 as $inp2) {
    $stmt->execute(['ini' => $inp2]);

    while ($row3 = $stmt->fetch()) {
        // echo $row2[0];
        $out2[] = '{
          "type": "Feature",
          "geometry": ' . $row3[0] . ', "properties": {
          }}' ;
      }    // $dbh = null;
    }
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}

// print_r($row);

// $stmt = $dbh->prepare('SELECT ST_AsGeoJSON(:row)');

  $dbh = null;

  $data = '{
    "type": "FeatureCollection",
    "features": [';

  foreach($out as $feature) {
    $data = $data . $feature . ',';
  }
  foreach($out2 as $feature) {
    $data = $data . $feature . ',';
  }
  $data = rtrim($data,', ');
  $data = $data . '] }';

    print_r($data);
    // echo $data;
