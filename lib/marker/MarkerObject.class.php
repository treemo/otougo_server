<?php

include_once __DIR__ . '/../../config/load.php';
include_once __DIR__ . '/../mysql/Sql.class.php';

class MarkerObject {

	protected $errors;
    protected $id;
	protected $latitude;
	protected $lastUpdate;
	protected $longitude;
	protected $name;

	function __construct() {
		$this->errors = array();
		$this->id = 0;
		$this->latitude = 0;
		$this->lastUpdate = 0;
		$this->longitude = 0;
		$this->name = '';
	}



	// ==============================================================
	// create
	// ==============================================================
	public function create($arg) {
        $this->latitude = $arg['latitude'];
        $this->lastUpdate = $arg['lastUpdate'];
        $this->longitude = $arg['longitude'];
        $this->name = $arg['name'];
        $this->save();
	}



    // ==============================================================
	// delete
	// ==============================================================
	public function delete() {
		$sql = new Sql();

        return  $sql->prepare('DELETE FROM marker WHERE id = :id')
                    ->bindValue(':id', $this->id)
                    ->execute();
	}



	// ==============================================================
	// error
	// ==============================================================
	public function getError() {
		return $this->errors;
	}

	protected function _addError($text) {
		$this->errors[] = $text;
	}



	// ==============================================================
	// id
	// ==============================================================
	public function getId() {
		return $this->id;
	}



	// ==============================================================
	// load
	// ==============================================================
	public function load($id) {
		$sql = new Sql();

        $sql->prepare('SELECT id, latitude, longitude, UNIX_TIMESTAMP(last_update) AS last_update, name FROM marker WHERE id = :id')
            ->bindValue(':id', $id)
            ->execute();

        if ( !($data = $sql->fetch()) ) {
            return null;
        }

		$this->id = intval( $data['id'] );
		$this->latitude = floatval( $data['latitude'] );
        $this->lastUpdate = intval( $data['last_update'] );
		$this->longitude = floatval( $data['longitude'] );
		$this->name = $data['name'];

		return $this;
	}



	// ==============================================================
	// save
	// ==============================================================
	public function save() {
		$sql = new Sql();
        $sql->prepare('INSERT INTO marker (id,type,latitude,longitude,name,last_update) VALUES (:id,:type,:latitude,:longitude,:name,FROM_UNIXTIME(:last_update)) ON DUPLICATE KEY UPDATE latitude=:latitude, longitude=:longitude, name=:name, last_update=FROM_UNIXTIME(:last_update)')
            ->bindValue(':id', empty($this->id) ? null : $this->id)
            ->bindValue(':type', get_class($this))
            ->bindValue(':latitude', $this->latitude)
            ->bindValue(':longitude', $this->longitude)
            ->bindValue(':name', $this->name)
            ->bindValue(':last_update', $this->lastUpdate)
            ->execute();

        if(empty($this->id)) {
            $this->id = $sql->lastInsertId();
        }

		return $this;
	}



    // ==============================================================
	// send data to client
	// ==============================================================
	public function getData() {
		return array(
			'id' 		=> $this->id,
			'latitude' 	=> $this->latitude,
			'lastUpdate' 	=> $this->lastUpdate,
			'longitude' => $this->longitude,
			'name'      => $this->name,
		);
	}
	
	public function __toString()
    {
        return json_encode( $this->getData() );
    }
}