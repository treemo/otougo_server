<?php

include_once __DIR__ . '/../../config/load.php';
include_once __DIR__ . '/../mysql/Sql.class.php';

class Stats {

	public static function addAction($action, $dataList) {

        $sql = new Sql();
        
        $dataList['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        $sql->prepare('INSERT INTO stat_use_' . $action . ' (' . implode(',', array_keys($dataList)) . ') VALUES (:' . implode(',:', array_keys($dataList)) . ')');
        
        foreach($dataList as $name => $data) {
            $sql->bindValue(":$name", $data);
        }

        return $sql->execute();
	}
    
    public static function addActionGetMarker($latitude, $longitude, $distance, $timeUpdate = 0, $filtre = '') {
        self::addAction('get_marker', array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'distance' => $distance,
            'timeUpdate' => $timeUpdate,
            'filtre' => $filtre,
        ));
	}
    
    public static function addActionGetMarkerInfo($id, $type) {
        self::addAction('get_marker_info', array(
            'marker_id' => $id,
            'type' => $type,
        ));
	}
}