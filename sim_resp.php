<?php

require_once 'db_config.php';

$time = $_POST['time'];

$step = $_POST['step'];

$choi = $_POST['button_action'];
echo $choi;

// foreach ($dbh->query("SELECT id, AsText(centroid), population, zone, spots  from coord WHERE
// (
//     zone IS NOT NULL
//   AND
//     spots IS NOT NULL
// );
foreach ($dbh->query("SELECT id, AsText(centroid), population, zone, spots  from coord
 ") as $row) {
    // print_r($row);

    $id[] = $row[0];
    $centroid[] = $row[1];
    $population[] = $row[2];
    $zone[] = $row[3];
    $spots[] = $row[4];
}
// if ($id == null) {
//     // echo "You have to initialise the DB with zones and parking spots before the simulation can be used.";
//     exit("You have to initialise the DB with zones and parking spots before the simulation can be used.");
// }

// echo $id[0];
$stmt = $dbh->prepare("SELECT * from zones WHERE time = ?");
$exp = array();

$i = -1;
foreach ($id as $polyg) {
    $i++;
    $keys = array('id', 'centroid', 'taken_spots');
    $taken_spots = "null";

    if (($zone[$i] !== null) && ($spots[$i] !== null)) {
        // echo $zone[$i];
        $stmt->execute([$time]);
        $row12 = $stmt->fetch();

        //Taken spots are calculated here
        $steady_dem = 0.2 * $population[$i];
        $taken_spots = ceil($steady_dem + ($row12[$zone[$i]] * ($spots[$i] - $steady_dem)));
        if ($taken_spots > $spots[$i]) { $taken_spots = $spots[$i];}
        ////
    }
    $values = array($polyg, $centroid[$i], $taken_spots);
    $exp = array_combine($keys, $values);

    $out[] = json_encode($exp);
}

$data = '{
"time": ' . $time . ', ' . '
"polygons": [';

foreach ($out as $polygon) {
    $data = $data . $polygon . ',';
}

$data = rtrim($data, ', ');

$data = $data . '] }';

$time = ($time * 60) + $step;
// $time = $tim;

function convertToHoursMins($time, $format = '%1d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}


echo convertToHoursMins($time);

// echo $data;

// echo $choi;
