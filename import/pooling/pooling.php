<?php
date_default_timezone_set('Europe/Paris');
$result = json_decode( file_get_contents('http://odata76.cloudapp.net/v1/opendata76/Covoiturages?$filter=&format=json'));

foreach($result->d as $data) {
    
    $date = $data->Timestamp;
    list($A, $M, $J ) = explode("-", $date);
    $J = substr($J, 0, 2);

    $result = download("http://www.galichon.com/codesgeo/ville.php?dept=".$data->ninseeco0."&dep=1");
    $result = explode('</center></td></td></tr><tr><td><center>', $result);
    $result = explode('</center></td><td><center>', $result[1]);
    $nomCommune = $result[0];
echo ("https://maps.googleapis.com/maps/api/geocode/json?address=".$nomCommune." ".$data->route."&sensor=true&key=AIzaSyDmVdOuLbiYGk62P84Qp9geplHOsutk2z0");

$result2 = json_decode( file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".  $nomCommune ." ". $data->route ."&sensor=true_or_false&key=AIzaSyDmVdOuLbiYGk62P84Qp9geplHOsutk2z0"));

die($result2);

    MarkerManager::add('pooling', array(
        'route'				=> $data->route,
        'lastUpdate'	    => mktime(0, 0, 0, $M, $J, $A),
        'categori'			=> $data->categori0,
        'ninseeCode'		=> $data->ninseeco0,
        'nbreplac'			=> $data->nbreplac0,
        'typeAir'			=> $data->typedair0,
        'coteRoute'			=> $data->coteaire0,
        'nomCommune'		=> $nomCommune,
    ));
}
