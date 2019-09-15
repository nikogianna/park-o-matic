<?php

require_once 'db_config.php';
// $user = 'root';
// $pass = 'root';
//
// try {
//     $dbh = new PDO('mysql:host=127.0.0.1;port=8889;dbname=Park', $user, $pass);
// } catch (PDOException $e) {
//     echo "Error!: " . $e->getMessage() . "<br/>";
//     die();
// }

// Clean DB From Previous Data
$sql = 'DELETE FROM coord;';
$sql.= 'ALTER TABLE coord AUTO_INCREMENT=1';
$stmt = $dbh->prepare($sql);
$stmt->execute();


$kml = simplexml_load_file($path);
// $kml = simplexml_load_file('nm.kml');

$i = -1;
foreach ($kml->Document->Folder as $pm) {
    foreach ($pm->Placemark as $placemark) {
        $i++;
        $desc = (string)$placemark->description;
        $desc = strip_tags($desc) . '<br/>';
        $re = '/(?<=gid: )\S+/i';
        $re2 = '/(?<=ESYE_CODE: )\S+/i';
        $re3 = '/(?<=Population: )\S+/i';

        preg_match($re, $desc, $match);
        $id[$i] = $match[0];
        preg_match($re2, $desc, $match);
        $code[$i] = $match[0];
        preg_match($re3, $desc, $match);
        $population[$i] = $match[0];
        $name[$i] = (string)$placemark->name;

        $outer_polyg = array();

        if (isset($placemark->MultiGeometry->MultiGeometry)) {
            foreach ($placemark->MultiGeometry->MultiGeometry->Polygon as $polyg) {
                $outer_polyg[] = $polyg->outerBoundaryIs->LinearRing->coordinates;
            }
        } else {
            foreach ($placemark->MultiGeometry->Polygon as $polyg) {
                $outer_polyg[] = $polyg->outerBoundaryIs->LinearRing->coordinates;
            }
        }
        $out_polyg = (sizeof($outer_polyg) > 1 ? $outer_polyg[1] : $outer_polyg[0]);
        $polygon1 = explode(' ', (string)$outer_polyg[0]);
        $polygonia2[$i] = '';
        if (sizeof($outer_polyg) > 1) {
            $polygon2 = explode(' ', (string)$outer_polyg[1]);

            for ($x = 0; $x <= sizeof($polygon2) - 1; $x++) {
                $corners2[$i][$x][0] = explode(",", $polygon2[$x])[0];
                $corners2[$i][$x][1] = explode(",", $polygon2[$x])[1];
                $polygonia2[$i] =  $polygonia2[$i] . $corners2[$i][$x][0] . ' ' . $corners2[$i][$x][1] . ',';
            }
            $polygonia2[$i] = rtrim($polygonia2[$i], ', ');
        }
        unset($outer_polyg);
        $polygonia[$i] = '';

        for ($x = 0; $x <= sizeof($polygon1) - 1; $x++) {
            $corners[$i][$x][0] = explode(",", $polygon1[$x])[0];
            $corners[$i][$x][1] = explode(",", $polygon1[$x])[1];
            $polygonia[$i] =  $polygonia[$i] . $corners[$i][$x][0] . ' ' . $corners[$i][$x][1] . ',';
        }
        $polygonia[$i] = rtrim($polygonia[$i], ', ');
    }
}

//In case population is given to be NULL, we assign the average population of the non-NULL polygons
$a = array_filter($population);
$average_population = array_sum($a)/count($a);
////

for ($i = 0; $i <= sizeof($id); $i++) {

    //See previous note
    if ($population[$i] == null) {
        $population[$i] = $average_population;
    }
    ////

    if ($polygonia2[$i] != '') {
        $stmt = $dbh->prepare('INSERT INTO coord (name, gid, population,centroid, multipol)
    VALUES (:name, :gid, :population,ST_Centroid(ST_GeomFromText(:multipolygon)),ST_GeomFromText(:multipolygon))');

        $stmt->execute(['name' => $name[$i],
        'gid' => $id[$i],
        'population' => $population[$i],
        'multipolygon' => 'MULTIPOLYGON(((' . $polygonia[$i] . ')),' . '((' . $polygonia2[$i] . ')))'
    ]);
    } else {
        $stmt = $dbh->prepare('INSERT INTO coord (name, gid, population,polygonx,centroid)
  VALUES (:name, :gid, :population, ST_GeomFromText(:polygonx), ST_Centroid(ST_GeomFromText(:polygonx)))');

        $stmt->execute(['name' => $name[$i],
      'gid' => $id[$i],
      'population' => $population[$i],
      'polygonx' => 'POLYGON((' . $polygonia[$i] . '))'
  ]);
    }
}
// $dbh = null;
