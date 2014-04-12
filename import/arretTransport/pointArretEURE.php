<?php
date_default_timezone_set('Europe/Paris');
$result = json_decode( file_get_contents('http://odata76.cloudapp.net/v1/opendata27/CG27TRANSPORTSPointsArret?$filter=&format=json'));

foreach($result->d as $data) {
    
    $date = $data->Timestamp;
    list($A, $M, $J ) = explode("-", $date);
    $J = substr($J, 0, 2);

    MarkerManager::add('common', array(
        'name'				=> $data->intitule,
        'lastUpdate'	    => mktime(0, 0, 0, $M, $J, $A),
        'longitude'			=> $data->longitude,
        'latitude'			=> $data->latitude,
    ));
}
