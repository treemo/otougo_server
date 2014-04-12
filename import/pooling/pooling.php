<?php
if ( UPDATE_TYPE <= MONTH) {
    date_default_timezone_set('Europe/Paris');
    $result = json_decode( file_get_contents('http://odata76.cloudapp.net/v1/opendata76/Covoiturages?$filter=&format=json'));

    foreach($result->d as $data) {
        
        $date = $data->Timestamp;
        list($A, $M, $J ) = explode("-", $date);
        $J = substr($J, 0, 2);

        $result = download("http://www.galichon.com/codesgeo/ville.php?dept=".$data->ninseeco0."&dep=1");
        $result = explode('</center></td></td></tr><tr><td><center>', $result);
        if (  isset($result[1]) ) {
            $result = explode('</center></td><td><center>', $result[1]);
        }
        $nomCommune = trim($result[0]);
        $route = str_replace(' ','',$data->route);
        $route = trim($route);
               
        $jsonUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($nomCommune).urlencode($route)."&sensor=false&key=AIzaSyDmVdOuLbiYGk62P84Qp9geplHOsutk2z0";
        $geocurl = curl_init();
        curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
        curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
        curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);
        $geofile = curl_exec($geocurl);
        $Obj = json_decode($geofile);

        MarkerManager::add('pooling', array(
            'route'             => $data->route,
            'lastUpdate'        => mktime(0, 0, 0, $M, $J, $A),
            'categori'          => $data->categori0,
            'ninseeCode'        => $data->ninseeco0,
            'nbreplac'          => $data->nbreplac0,
            'typeAir'           => $data->typedair0,
            'coteRoute'         => $data->coteaire0,
            'name'              => $nomCommune,
            'latitude'          => empty($Obj->results['0']->geometry->location->lat) ? 1 :  $Obj->results['0']->geometry->location->lat,
            'longitude'         => empty($Obj->results['0']->geometry->location->lng) ? 1 :  $Obj->results['0']->geometry->location->lng,
        ));
    }
}
