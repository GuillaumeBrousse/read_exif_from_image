<?php 
require './vendor/autoload.php';
//emplacement de l'image 
$picture_src = "./paris.jpg";

//lecture des métadonnées exif
// $exif = exif_read_data($picture_src, 'IFD0');
$exif = exif_read_data($picture_src, 0, true);

// Check if there is some metadata
if($exif===false){
    dump("[ERROR] No EXIF metadata");
} else {
    if (isset($exif["EXIF"]["DateTimeOriginal"])) {
        // Convert to french date format
        $date_prise = date("d/m/Y H:i:s", strtotime($exif["EXIF"]["DateTimeOriginal"]));
    }


    /**
     * latitude and longitude are formated like Degrees minutes secondes, we need to format it as a decimal.
     * So we need to divide Minutes by 60 and seconds by 3600 and then make a sum of everything (Degrees, minutes, secondes).
     * That's how we got the position in decimal. 
    */

    // Get latitude and convert to decimal format
    if (isset($exif["GPS"]["GPSLatitude"])) {
        $gps_lat = $exif["GPS"]["GPSLatitude"];
        $gps_lat_deg = explode("/", $gps_lat[0]);
        $gps_lat_deg = $gps_lat_deg[0]/$gps_lat_deg[1];
        $gps_lat_sec = explode("/", $gps_lat[2]);
        $gps_lat_sec = ($gps_lat_sec[0]/$gps_lat_sec[1])/3600;
        $gps_lat_min = explode("/", $gps_lat[1]);
        $gps_lat_min = $gps_lat_min[0]/$gps_lat_min[1]/60;
        $gps_lat_dec = $gps_lat_deg + $gps_lat_min + $gps_lat_sec;
    }
    // Get longitude and convert to decimal format
    if (isset($exif["GPS"]["GPSLongitude"])) {
        $gps_lon = $exif["GPS"]["GPSLongitude"];
        $gps_lon_deg = explode("/", $gps_lon[0]);
        $gps_lon_deg = $gps_lon_deg[0]/$gps_lon_deg[1];
        $gps_lon_sec = explode("/", $gps_lon[2]);
        $gps_lon_sec = ($gps_lon_sec[0]/$gps_lon_sec[1])/3600;
        $gps_lon_min = explode("/", $gps_lon[1]);
        $gps_lon_min = $gps_lon_min[0]/$gps_lon_min[1]/60;
        $gps_lon_dec = $gps_lon_deg + $gps_lon_min + $gps_lon_sec;
    }

    // Get altitude and convert to decimal format
    if (isset($exif["GPS"]["GPSAltitude"])) {
        $gps_alt = $exif["GPS"]["GPSAltitude"];
        $gps_alt = explode("/", $gps_alt);
        $gps_alt = $gps_alt[0]/$gps_alt[1];
    }

    if (isset($gps_lat_dec) && isset($gps_lon_dec)) {
        // Generate string with coordinate
        $gps_gps = "[" . round($gps_lat_dec, 8) . ", " . round($gps_lon_dec, 8) . "]";
        // display coordinate
        dump($gps_gps);
    }
}

// echo 'EXIF Headers:' . '<br>'; 
// print("<pre>".print_r($exif, true)."</pre>"); 
