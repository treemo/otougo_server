<?php

include_once __DIR__ . '/MarkerObject.class.php';

class MarkerArretTransport extends MarkerObject {
	protected $lastStation;

	function __construct() {
		parent::__construct();
		$this->lastStation = 0;
	}



	// ==============================================================
	// create
	// ==============================================================
	public function create($arg) {
        parent::create($arg);

        $this->lastStation = $arg['lastStation'];
        $this->save(false);
	}



	// ==============================================================
	// delete
	// ==============================================================
	public function delete() {
        parent::delete();

		$sql = new Sql();

        return  $sql->prepare('DELETE FROM marker_arret_transport WHERE marker_id = :id')
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

        $sql->prepare('SELECT * FROM marker_arret_transport WHERE marker_id = :id')
            ->bindValue(':id', $id)
            ->execute();

        if ( !($data = $sql->fetch()) ) {
            return null;
        }

		$this->lastStation = intval( $data['last_station'] );

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

        $sql->prepare('INSERT INTO marker_arret_transport (marker_id,last_station) VALUES (:marker_id,:last_station) ON DUPLICATE KEY UPDATE last_station=:last_station')
            ->bindValue(':marker_id', $this->id)
            ->bindValue(':last_station', $this->lastStation)
            ->execute();

		return $this;
	}



    // ==============================================================
	// send data to client
	// ==============================================================
	public function getData() {
		return array_merge(parent::getData(), array(
			'lastStation'   => $this->lastStation,
		));
	}
}