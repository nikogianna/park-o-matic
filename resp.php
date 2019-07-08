<?php

require_once 'db_config.php';

foreach ($dbh->query("SELECT AsText(polygonx), id, AsText(centroid), population from coord WHERE polygonx IS NOT NULL ") as $row) {
    // print_r($row);
    $ini[] = $row[0];
    $idi[] = $row[1];
    $cen[] = $row[2];
    $pop[] = $row[3];
}

foreach ($dbh->query("SELECT AsText(multipol), id, AsText(centroid), population from coord WHERE multipol IS NOT NULL") as $row3) {
    $ini2[] = $row3[0];
    $idi2[] = $row3[1];
    $cen2[] = $row3[2];
    $pop2[] = $row3[3];
}

$stmt = $dbh->prepare("SELECT ST_AsGeoJSON(ST_GeomFromText(:ini)), ST_AsGeoJSON(ST_GeomFromText(:centr));");

$i = -1;
foreach ($ini as $inp) {
    $i++;
    $stmt->execute(['ini' => $inp,
      'centr' => $cen[$i]
]);

// if ($zone[$i] == null) { $zone[$i] = 'null';}
    while ($row2 = $stmt->fetch()) {
        $out[] = '{
      "type": "Feature",
      "geometry": ' . $row2[0] . ', "properties": { "id": ' . $idi[$i] . ', ' . '"centroid": ' . ' ' . $row2[1] . ', '
       . '"population": ' . ' ' . $pop[$i] . ' '
       . '}}' ;
    }
}

$i = -1;
foreach ($ini2 as $inp2) {
    $i++;
    $stmt->execute(['ini' => $inp2,
  'centr' => $cen2[$i]
]);
// if ($zone2[$i] == null) { $zone2[$i] = 'null';}

    while ($row4 = $stmt->fetch()) {
        $out2[] = '{
      "type": "Feature",
      "geometry": ' . $row4[0] . ', "properties": { "id": ' . $idi2[$i] . ', ' . '"centroid": ' . ' ' . $row4[1] . ', '
        . '"population": ' . ' ' . $pop2[$i] . ' '
      . '}}' ;
    }
}

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

if ($registration == "success") {
    // some action goes here under php
    echo json_encode(array("abc"=>$resp));
}
