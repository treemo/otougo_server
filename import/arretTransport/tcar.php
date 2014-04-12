<?php

function importLigne($id) {

    $inputData = array();
    $result = download("http://www.crea-astuce.fr/horaires_ligne/index.asp?rub_code=6&lign_id=$id&thm_id=421");
    if (empty($result)) return;
    $result = explode('name="routeForm"', $result);
    if (empty($result)) return;
    $result = explode('</form,', $result[1]);
    $result = strip_tags($result[0], '<input>');
    $result = explode('>', $result);
    
    if (empty($result)) return;

    $id = 0;
    $nameInputIncrement = '';
    foreach($result as $data) {

        if (strpos($data, 'input') == false) {
            continue;
        }

        $data = explode('"', $data);
        
        if ($nameInputIncrement == $data[3]) {
            $id++;
        }
        elseif (empty($nameInputIncrement)) {
            $nameInputIncrement = $data[3];
        }


        if (empty($inputData[$id])) {
            $inputData[$id] = array();
        }

        $inputData[$id][$data[3]] = $data[5];
    }

    $last = null;
    foreach($inputData as $data) {
        $last = MarkerManager::add('arretTransport', array(
            'latitude'      => $data['X'],
            'longitude'     => $data['Y'],
            'name'          => $data['Nom'],
            'lastUpdate'    => time(),
            'lastStation'    => empty($last) ? null : $last->getId(),
        ));
    }
}

$ligneList = array(13,63,71,17,62,70,12,56,21,69,52,5,16,11,27,24,26,55,54,61,73,25,20,4,68,40,13,78,81,59,30,29,39,47,2,74,18,44,53,75,60,66,8,31,112,76,58,65,7,36,77,38,46,57,35,72,107,109,111,1,14,34,42,64,115,41,130,131,132,133,136,135,138,139,140,142,141,134,92,94,96,98,100,101,137,90,23,84,51,18,137);
count($ligneList);
foreach($ligneList as $ligne) {
    importLigne($ligne);
    sleep(1);
}