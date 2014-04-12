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
            "code"      => "frurd", ];

$gare[] = [ "longitude" => 2.3800,20
            "latitude"  => 48.840172,
            "Nom"       => "Paris Bercy",
            "code"      => "frpbe", ];



foreach ( $gare as $v ) {
    loadStation($v["longitude"], $v["latitude"], $v["Nom"], $v["code"], null);
}
