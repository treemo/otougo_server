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
    
    public static function getNavigateur() {
        if (!ini_get('browscap') || !is_file(ini_get('browscap'))) {
			die('browscap ini directive not set in php.ini');
		}
        
        $tableSql = array(
            'stat_use_get_marker',
            'stat_use_get_marker_info',
        );
        
        $stats = array(
            'browser'			=> array(),
            'browser_version'	=> array(),
            'os'				=> array(),
        );
        
        foreach ($tableSql as $table) {
            $sql = new Sql();

            $sql->prepare("SELECT user_agent FROM $table")
                ->execute();

            while( $data = $sql->fetch()) {
                $browser = get_browser($data['user_agent']);

                if (empty($stats['browser'][ $browser->browser ])) {
                    $stats['browser'][ $browser->browser ] = 1;
                }
                else {
                    $stats['browser'][ $browser->browser ]++;
                }

                if (empty($stats['browser_version'][ $browser->parent ])) {
                    $stats['browser_version'][ $browser->parent ] = 1;
                }
                else {
                    $stats['browser_version'][ $browser->parent ]++;
                }

                if (empty($stats['os'][ $browser->platform ])) {
                    $stats['os'][ $browser->platform ] = 1;
                }
                else {
                    $stats['os'][ $browser->platform ]++;
                }
            }
		}

		return $stats;
	}
}