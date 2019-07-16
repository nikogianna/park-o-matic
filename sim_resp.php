<?php

require_once 'db_config.php';

// $_SESSION = array();
// session_destroy();
session_start();

if (!isset($_SESSION["time"])) {
    $_SESSION["time"] = 0;
} else {
    $time = $_SESSION["time"];
}

if (!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
} else {
    $step = $_SESSION["step"];
}

$temp_time = $_POST['time'];

$temp_step = $_POST['step'];

$choi = $_POST['button_action'];

// echo $_SESSION["step"];
// echo '</br>';
//
// echo $step;


if ($choi == 'action') {

    $time = $temp_time * 60;
    $_SESSION["step"] = $temp_step;
} elseif ($choi == 'next') {
    $time = $time + $step;
} elseif ($choi == 'previous') {

    $time = $time - $step;

    if ($time < 0) {
      //24 * 60 = 1440
      $time = 1440 - $step;
    }
}

// echo '</br>';
//
// echo $time;
$_SESSION["time"] = $time;
$hour = floor($time / 60);
// echo '</br>';
//
// echo $hour;

function convertToHoursMins($time, $format = '%1d:%02d')
{
    // if ($time < 1) {
    //     return;
    // }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);

}
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

$stmt = $dbh->prepare("SELECT * from zones WHERE time = ?");
$exp = array();

$i = -1;
foreach ($id as $polyg) {
    $i++;
    $keys = array('id', 'centroid', 'taken_spots');
    $taken_spots = "null";

    if (($zone[$i] !== null) && ($spots[$i] !== null)) {
        // echo $zone[$i];
        $stmt->execute([$hour]);
        $row12 = $stmt->fetch();

        //Taken spots are calculated here
        $steady_dem = 0.2 * $population[$i];
        $taken_spots = ceil($steady_dem + ($row12[$zone[$i]] * ($spots[$i] - $steady_dem)));
        if ($taken_spots > $spots[$i]) {
            $taken_spots = $spots[$i];
        }
        ////
    }
    $values = array($polyg, $centroid[$i], $taken_spots);
    $exp = array_combine($keys, $values);

    $out[] = json_encode($exp);
}

$data = '{
"time": ' . '"' . convertToHoursMins($time) . '"' . ', ' . '
"polygons": [';

foreach ($out as $polygon) {
    $data = $data . $polygon . ',';
}

$data = rtrim($data, ', ');

$data = $data . '] }';

echo $data;

// echo $choi;
