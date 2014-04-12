<?php

$apiKey = 'c829c7f19435d22dc7ced0ed866ae02cbc5fa630';
$result = json_decode( file_get_contents("https://api.jcdecaux.com/vls/v1/stations?apiKey=$apiKey"));

foreach($result as $data) {
    MarkerManager::add('cycle', array(
        'latitude'      => $data->position->lat,
        'longitude'     => $data->position->lng,
        'name'          => $data->name,
        'lastUpdate'    => round($data->last_update / 1000),
        'nb_available'  => $data->available_bikes,
        'total'         => $data->bike_stands,
    ));
}