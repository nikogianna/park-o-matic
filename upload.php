<?php

// print_r($_FILES['fileToUpload']);
// print_r($_FILES['fileToUpload']['error']);
switch ($_FILES['fileToUpload']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            echo('No file chosen. Please choose a file to upload');
            return false;
            // break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo('Exceeded filesize limit.');
            // break;
            return false;
        default:
            echo('Unknown errors.');
            return false;
    }

if (!empty($_FILES['fileToUpload'])) {
    $path = "/Applications/MAMP/uploads/";
    $path = $path . basename($_FILES['fileToUpload']['name']);

    if (!file_exists($path)) {
        $mime = mime_content_type($_FILES['fileToUpload']['tmp_name']);
        if ($mime !== 'text/xml') {
            echo "The uploaded file needs to be a valid kml file";
            return false;
        }

        $file_parts = pathinfo($_FILES['fileToUpload']['name']);
        if ($file_parts['extension'] !== 'kml') {
            echo "The uploaded file needs to be a valid kml file";
            return false;
        }

        // $path = "/Applications/MAMP/uploads/";
        // $path = $path . basename($_FILES['fileToUpload']['name']);
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $path)) {
            echo "The file ".  basename($_FILES['fileToUpload']['name']).
    " has been uploaded";
        } else {
            echo "There was an error uploading the file, please try again!";
            return false;
        }
    }
    // else {
    //   // echo "File already exists";
    // }
    include 'tes_kml.php';
    header("location: map_edit.html");
    // echo $path;
}
