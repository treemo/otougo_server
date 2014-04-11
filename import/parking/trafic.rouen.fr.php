<?php

$result = download('http://trafic.rouen.fr/parking');
$result = explode('"features":', $result);
$result = $result[1];
$result = explode(',"title":"",', $result);
$result = $result[0];
$result = json_decode($result);

foreach($result as $data) {
    $data = $data->attributes;

    $pos = str_replace('POINT (', '', $data->field_emplacement);
    $pos = str_replace(')', '', $pos);
    list($longitude, $latitude) = explode(' ', $pos);
    
    $nbPlace = str_replace(' ', '', $data->field_places_libres);
    $nbTotal = str_replace(' ', '', $data->field_places_occupees);

    MarkerManager::add('parking', array(
        'latitude'      => $latitude,
        'longitude'     => $longitude,
        'name'          => $data->title,
        'lastUpdate'    => time(),
        'nb_available'  => $nbPlace,
        'total'         => $nbTotal + $nbPlace,
    ));
}