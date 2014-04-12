<?php

function loadStation($x, $y, $name, $key, $lastStation = null) {

    $schedule = array();
    $scheduleData = array();

    $result = download("http://www.gares-en-mouvement.com/fr/$key/horaires-temps-reel/dep/");

    $result = explode('<tbody>', $result);
    $result = explode('</tbody>', $result[1]);

    $result = strip_tags($result[0], '<tr><td>');
    $result = explode('>', $result);

    $i = 0;
    foreach($result as $data) {

        if ( strpos($data, '<tr') !== false || strpos($data, '<td') !== false ||  strpos($data, '</') === false ) {
            continue;
        }

        $v = explode('</', $data);
        $v = reset($v);
        $v = trim($v);

        $scheduleData[ floor($i / 8) ][$i % 8] = $v;
        $i++;
    }

    foreach ($scheduleData as $value) {
        $h = substr($value[3], 0, 2);
        if(empty($schedule[$h])) {
            $schedule[$h] = array();
        }

        $schedule[$h][] = substr($value[3], -2);
    }

    MarkerManager::add('common', array(
        'latitude'      => $x,
        'longitude'     => $y,
        'name'          => $name,
        'lastUpdate'    => time(),
        'lastStation'   => $lastStation,
        'schedule'      => empty($schedule) ? null : array( strtotime('midnight') => $schedule),
    ));
}

$gare[] = [ "longitude" => 1.094206,
            "latitude"  => 49.449239,
            "Nom"       => "Gare de rouen",
            "code"      => "frurd",];

$gare[] = [ "longitude" => 2.380020,
            "latitude"  => 48.840172,
            "Nom"       => "Paris Bercy",
            "code"      => "frpbe",];

$gare[] = [ "longitude" => 2.366876,
            "latitude"  => 48.841080,
            "Nom"       => "Paris Austerlitz",
            "code"      => "frpaz",];

$gare[] = [ "longitude" => 2.35869,
            "latitude"  => 48.876098,
            "Nom"       => "Paris Est",
            "code"      => "frpst",];

$gare[] = [ "longitude" => 2.374225,
            "latitude"  => 48.844737,
            "Nom"       => "Paris Gare de Lyon",
            "code"      => "frply",];

$gare[] = [ "longitude" => 2.319101,
            "latitude"  => 48.840755,
            "Nom"       => "Paris Montparnasse",
            "code"      => "frpmo",];

$gare[] = [ "longitude" => 2.324765,
            "latitude"  => 48.876937,
            "Nom"       => "Paris St Lazare",
            "code"      => "frpsl",];

$gare[] = [ "longitude" => 1.081632,
            "latitude"  => 49.920007,
            "Nom"       => "Dieppe",
            "code"      => "frafd",];

$gare[] = [ "longitude" => 0.124517,
            "latitude"  => 49.492849,
            "Nom"       => "Le Havre",
            "code"      => "fraez",];

$gare[] = [ "longitude" => 1.098029,
            "latitude"  => 49.34179,
            "Nom"       => "Oissel",
            "code"      => "froil",];

$gare[] = [ "longitude" => 0.753805,
            "latitude"  => 49.621776,
            "Nom"       => "Yvetot",
            "code"      => "fryve",];

$gare[] = [ "longitude" => -0.556099,
            "latitude"  => 44.82607,
            "Nom"       => "Bordeaux Saint Jean",
            "code"      => "frboj",];




foreach ( $gare as $v ) {
    loadStation($v["longitude"], $v["latitude"], $v["Nom"], $v["code"], null);
}
