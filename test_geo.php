<?php

$user = 'root';
$pass = 'root';
// $name = 'assasas';

try {
    $dbh = new PDO('mysql:host=127.0.0.1;port=8889;dbname=Park', $user, $pass);

    foreach ($dbh->query("SELECT AsText(polygonx), id, AsText(centroid), population from coord WHERE polygonx IS NOT NULL ") as $row) {
        // print_r($row);
        $ini[] = $row[0];
        $idi[] = $row[1];
        $cen[] = $row[2];
        $zone[] = $row[3];
    }

    foreach ($dbh->query("SELECT AsText(multipol), id, AsText(centroid), population from coord WHERE multipol IS NOT NULL") as $row3) {
        $ini2[] = $row3[0];
        $idi2[] = $row3[1];
        $cen2[] = $row3[2];
        $zone2[] = $row3[3];
    }

    $stmt = $dbh->prepare("SELECT ST_AsGeoJSON(ST_GeomFromText(:ini)), ST_AsGeoJSON(ST_GeomFromText(:centr));");

    $i = -1;
    foreach ($ini as $inp) {
        $i++;
        $stmt->execute(['ini' => $inp,
      'centr' => $cen[$i]
    ]);

        while ($row2 = $stmt->fetch()) {

            $out[] = '{
          "type": "Feature",
          "geometry": ' . $row2[0] . ', "properties": { "id": ' . $idi[$i] . ', ' . '"centroid": ' . ' ' . $row2[1] . ', '
            . '"population": ' . ' ' . $zone[$i] . ' '
          . '}}' ;
        }
    }

    $i = -1;
    foreach ($ini2 as $inp2) {
        $i++;
        $stmt->execute(['ini' => $inp2,
      'centr' => $cen2[$i]
    ]);

        while ($row3 = $stmt->fetch()) {

            $out2[] = '{
          "type": "Feature",
          "geometry": ' . $row3[0] . ', "properties": { "id": ' . $idi2[$i] . ', ' . '"centroid": ' . ' ' . $row3[1] . ', '
            . '"population": ' . ' ' . $zone2[$i] . ' '
          . '}}' ;
        }
    }
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}

  $dbh = null;

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

  print_r($data);
