<?php

$user = 'root';
$pass = 'root';
// $name = 'assasas';

try {
    $dbh = new PDO('mysql:host=127.0.0.1;port=8889;dbname=Park', $user, $pass);

    foreach($dbh->query("SELECT * from test") as $row) {
        // print_r($row);
    }
    // $stmt = $dbh->prepare('INSERT INTO coord (name)
    // VALUES (:name)');
    // // $dbh = "INSERT INTO coord(name) VALUES (:name)";
    // // $stmt= $pdo->prepare($dbh);
    // $stmt->execute(['name' => $name]);
    // $dbh = null;
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$kml = simplexml_load_file('nm.kml');

$i = -1;
foreach ($kml->Document->Folder as $pm) {
    foreach ($pm->Placemark as $placemark) {
  $i++;
  // print_r($placemark->description);
        $desc = (string)$placemark->description;
        $desc = strip_tags($desc) . '<br/>';
        // echo $desc;

        $re = '/(?<=gid: )\S+/i';
        $re2 = '/(?<=ESYE_CODE: )\S+/i';
        $re3 = '/(?<=Population: )\S+/i';

        preg_match($re, $desc, $match);
        // echo $match[0];
        $id[$i] = $match[0];
        preg_match($re2, $desc, $match);
        // echo $match[0];
        $code[$i] = $match[0];
        preg_match($re3, $desc, $match);
        // echo $match[0];
        $population[$i] = $match[0];

        // echo $id[$i] . ' ' . $code[$i] . ' ' . $population[$i] . '<br/>';
        $name[$i] = (string)$placemark->name;
        $pointin[$i] = (string)$placemark->MultiGeometry->Point->coordinates;
        $center = (string)$placemark->MultiGeometry->Point->coordinates;
        $point[$i][0] = explode(",", $center)[0];
        $point[$i][1] = explode(",", $center)[1];

        // print_r($placemark->MultiGeometry->Point->coordinates);
        $polygon = explode(' ', (string)$placemark->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates);
        $polygonia[$i] = '';

        for ($x = 0; $x <= sizeof($polygon) - 1; $x++) {
            // for ($y = 0; $y <= 1; $y++) {
                $corners[$i][$x][0] = explode(",", $polygon[$x])[0];
                $corners[$i][$x][1] = explode(",", $polygon[$x])[1];
                $polygonia[$i] =  $polygonia[$i] . $corners[$i][$x][0] . ' ' . $corners[$i][$x][1] . ',';
                // $y++;
                // $corners[$i][$x][$y] = explode(",", $polygon[$x])[$y];
            // }
        }
        $polygonia[$i] = rtrim($polygonia[$i],', ');
        // var_dump($corners[$i]);
        // var_dump($corners);
        // print_r($placemark->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates);
    }
}

// for ($i = 0; $i <= sizeof($id); $i++) {
//   // echo $name[$i] . '<br/>';
//   foreach($corners[$i] as $w){
//     // foreach($w as $r){
//
//     foreach($w as $key => $value){
//
// // echo $key . ' ' . $value . '<br/>';
// }
// }
// }
echo $polygonia[0] . '<br/>';
for ($i = 0; $i <= sizeof($id); $i++) {

    // echo $corners[$i][1][0] . '<br/>';
    // echo $name[$i] . ' ' . $id[$i] . ' ' . $code[$i] . ' ' . $population[$i] . ' ' . $point[$i][0] . ' ' . $point[$i][1] . ' ' . '<br/>';
    // $stmt = $dbh->prepare('INSERT INTO coord (name, pointx)
    // VALUES (:name, ST_PointFromText(:pointx))');
    // // $dbh = "INSERT INTO coord(name) VALUES (:name)";
    // // $stmt= $pdo->prepare($dbh);
    // $stmt->execute(['name' => $name[$i],
    //       'pointx' => $pointin[$i]
    // ]);

    // echo $polygonia[0] . '<br/>';

    $stmt = $dbh->prepare('INSERT INTO coord (name, gid, ESYE, population, polygonx, centroidx, multipol)
    VALUES (:name, :gid, :ESYE, :population, ST_GeomFromText(:polygonx),ST_Centroid(ST_GeomFromText(:polygonx)),
    ST_GeomFromText(:multipolygon))');

    // $dbh = "INSERT INTO coord(name) VALUES (:name)";
    // $stmt= $pdo->prepare($dbh);
    $stmt->execute(['name' => $name[$i],
        'gid' => $id[$i],
        'ESYE' => $code[$i],
        'population' => $population[$i],
        'pointx' => 'POINT('. $point[$i][1] . ' ' . $point[$i][0] . ')',
        'polygonx' => 'POLYGON((' . $polygonia[$i] . '))',
        'multipolygon' => 'MULTIPOLYGON(((' . $polygonia[$i] . ')),' . '((' . $polygonia[$i] . ')))'
    ]);


    // $dbh = "INSERT INTO coord (name)
    //     VALUES ('$nombre')";
    //     // use exec() because no results are returned
    //     $conn->exec($sql);
        // echo "New record created successfully";

}
$dbh = null;

// echo file_get_contents ( 'nm.kml');
