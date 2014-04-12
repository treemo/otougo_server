<?php
date_default_timezone_set('Europe/Paris');
$result = json_decode( file_get_contents('http://odata76.cloudapp.net/v1/opendata76/pointarretcoordonnees?$filter=&format=json'));

foreach($result->d as $data) {


	$date = $data->pa_datmaj;
	$A = substr($date, 0, 4); 
	$M = substr($date, 4, -2); 
	$J = substr($date, -2); 

    MarkerManager::add('common', array(
        'name'				=> $data->pa_nom_long,
        'cp'				=> $data->pa_cod_commu_ass,
        'lastUpdate'	    => mktime(0, 0, 0, $M, $J, $A),
        'longitude'			=> $data->pa_x_wgs84,
        'latitude'			=> $data->pa_y_wgs84,
    ));
}
