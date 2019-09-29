<?php

require_once 'db_config.php';

foreach ($dbh->query("SELECT AsText(polygonx), id, AsText(centroid), population, AsText(multipol), spots from coord") as $row) {
    $ini[] = $row[0];
    $idi[] = $row[1];
    $cen[] = $row[2];
    $pop[] = $row[3];
    $ini2[] = $row[4];
    $spots[] = $row[5];
}

$stmt = $dbh->prepare("SELECT ST_AsGeoJSON(ST_GeomFromText(:ini)), ST_AsGeoJSON(ST_GeomFromText(:centr));");

$i = -1;
foreach ($ini as $inp) {
    $i++;
    if ($inp !== null) {
        $stmt->execute(['ini' => $inp,
      'centr' => $cen[$i]
]);
    } elseif ($ini2[$i] !== null) {
        $stmt->execute(['ini' => $ini2[$i],
    'centr' => $cen[$i]
]);
    }

while ($row2 = $stmt->fetch()) {
        $out[] = '{
      "type": "Feature",
      "geometry": ' . $row2[0] . ', "properties": { "id": ' . $idi[$i] . ', ' . '"centroid": ' . ' ' . $row2[1] . ', '
       . '"population": ' . ' ' . $pop[$i] . ', '
        . '"spots": ' . ' "' . $spots[$i] . '" '
       . '}}' ;
    }
}

$data = '{
"type": "FeatureCollection",
"features": [';

foreach ($out as $feature) {
    $data = $data . $feature . ',';
}

$data = rtrim($data, ', ');
$data = $data . '] }';

$resp = $data;


echo json_encode(array("abc"=>$resp));
