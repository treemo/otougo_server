<?php

include_once __DIR__ . '/../../config/load.php';
include_once __DIR__ . '/../mysql/Sql.class.php';

class MarkerManager {

	private static $lastError = '';

	public static function add($type, $arg) {

        if (!self::loadclassMarker($type)) {
            return null;
        }

        $marker = new $type();
        $marker->create($arg);

		return $marker;
	}

	public static function getZone($latitude, $longitude, $distance, $timeUpdate = 0, $filtre = '') {
    
        if ($distance > 200000) { // 20 km
            return array('error' => 'max distance');
        }
        
        if (!empty($filtre)) {
            $filtre = preg_replace('/[^a-z]/i', '',$filtre);
            $filtre = ' AND type="Marker'.ucfirst($filtre).'"';
        }

		$sql = new Sql();
        $result = array();

        $sql->prepare('SELECT id, type FROM marker WHERE get_distance_metres(:latitude, :longitude, latitude, longitude) <= :distance AND last_update>FROM_UNIXTIME(:last_update)'.$filtre)
        $sql->prepare('SELECT id, type FROM marker WHERE get_distance_metres(:latitude, :longitude, latitude, longitude) <= :distance AND last_update>FROM_UNIXTIME(:last_update)'.$filtre)
            ->bindValue(':latitude', $latitude)
            ->bindValue(':longitude', $longitude)
            ->bindValue(':distance', $distance)
            ->bindValue(':last_update', $timeUpdate)
            ->execute();

		while ($donnee = $sql->fetch()) {

			if (!self::loadclassMarker($donnee['type'])) {
				continue;
			}

			$marker = new $donnee['type']();

			if (!$marker->load($donnee['id'])) {
				self::$lastError = 'load marker : '.json_encode($marker->getError());
				continue;
			}

            $index = strtolower( str_replace('Marker', '', $donnee['type']) );

            if (empty($result[ $index ])) {
                $result[ $index ] = array();
            }

            $result[ $index ][] = $marker->getData();
		}

        if (!empty(self::$lastError)) {
            $result['error'] = self::$lastError;
        }

        return $result;
	}

	public static function getLastError() {
		return self::$lastError;
	}

	public static function loadclassMarker(&$name) {
		if (strpos($name, '.') !== false) {
			self::$lastError = "class name \"$name\" is invalid";
			return false;
		}

		if (strpos($name, 'Marker') !== 0) {
			$name[0] = strtoupper($name[0]);
			$name = 'Marker'.$name;
		}
		
		if (class_exists($name)) { // deja inclue
			return true;
		}

		$path = __DIR__."/$name.class.php";

		if (!is_file($path)) {
			self::$lastError = "class \"$name\" not found";
			return false;
		}

		include $path;

		return true;
	}
}