<?php

include_once __DIR__ . '/MarkerObject.class.php';

class MarkerCommon extends MarkerObject {
	protected $lastStation;
	protected $schedule;

	function __construct() {
		parent::__construct();
		$this->lastStation = 0;
		$this->schedule = array();
	}



	// ==============================================================
	// create
	// ==============================================================
	public function create($arg) {
        parent::create($arg);

        $this->lastStation = empty($arg['lastStation']) ? null : $arg['lastStation'];
        $this->schedule = empty($arg['schedule']) ? array() : $arg['schedule'];
        $this->save(false);
	}



	// ==============================================================
	// delete
	// ==============================================================
	public function delete() {
        parent::delete();

        $sql = new Sql();
        $sql->prepare('DELETE FROM marker_schedule WHERE marker_id = :id')
                    ->bindValue(':id', $this->id)
                    ->execute();

		$sql = new Sql();
        return  $sql->prepare('DELETE FROM marker_common WHERE marker_id = :id')
                    ->bindValue(':id', $this->id)
                    ->execute();
	}



	// ==============================================================
	// load
	// ==============================================================
	public function load($id) {
        if (!parent::load($id)) {
			return null;
		}

		$sql = new Sql();

        $sql->prepare('SELECT * FROM marker_common WHERE marker_id = :id')
            ->bindValue(':id', $id)
            ->execute();

        if ( !($data = $sql->fetch()) ) {
            return null;
        }

		$this->lastStation = intval( $data['last_station'] );

        $sql = new Sql();

        $sql->prepare('SELECT UNIX_TIMESTAMP(day) AS day, hour, min FROM marker_schedule WHERE marker_id = :id')
            ->bindValue(':id', $id)
            ->execute();

        while ( ($data = $sql->fetch()) ) {
            if (empty($this->schedule[ $data['day'] ])) {
                $this->schedule[ $data['day'] ] = array();
            }

            if (empty($this->schedule[ $data['day'] ][ $data['hour'] ])) {
                $this->schedule[ $data['day'] ][ $data['hour'] ] = array();
            }

            $this->schedule[ $data['day'] ][ $data['hour'] ][] = $data['min'];
        }

		return $this;
	}



	// ==============================================================
	// save
	// ==============================================================
	public function save($callParent = true) {
        if ($callParent) {
            parent::save();
        }

		$sql = new Sql();

        $sql->prepare('INSERT INTO marker_common (marker_id,last_station) VALUES (:marker_id,:last_station) ON DUPLICATE KEY UPDATE last_station=:last_station')
            ->bindValue(':marker_id', $this->id)
            ->bindValue(':last_station', $this->lastStation)
            ->execute();

        $sql = new Sql();

        $sql->prepare('INSERT INTO marker_schedule (marker_id,day,hour,min) VALUES (:marker_id,FROM_UNIXTIME(:day),:hour,:min) ON DUPLICATE KEY UPDATE day=FROM_UNIXTIME(:day), hour=:hour, min=:min')
            ->bindValue(':marker_id', $this->id);

        if (!empty($this->schedule)) {
            foreach($this->schedule as $day => $data) {
                $sql->bindValue(':day', $day);
                foreach($data as $hour => $data2) {
                    $sql->bindValue(':hour', $hour);
                    foreach($data2 as $min) {
                        $sql->bindValue(':min', $min)
                            ->execute();
                    }
                }
            }
        }

		return $this;
	}



    // ==============================================================
	// send data to client
	// ==============================================================
	public function getData() {
		return array_merge(parent::getData(), array(
			'lastStation'   => $this->lastStation,
			'schedule'      => $this->schedule,
		));
	}
}