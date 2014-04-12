<?php

include_once __DIR__ . '/MarkerObject.class.php';

class MarkerCycle extends MarkerObject {

    protected $nbAvailable;
	protected $total;

	function __construct() {
		parent::__construct();
		$this->nbAvailable = 0;
		$this->total = 0;
	}



	// ==============================================================
	// create
	// ==============================================================
	public function create($arg) {
        parent::create($arg);

        $this->nbAvailable = $arg['nb_available'];
        $this->total = $arg['total'];
        $this->save(false);
	}



	// ==============================================================
	// delete
	// ==============================================================
	public function delete() {
        parent::delete();

		$sql = new Sql();

        return  $sql->prepare('DELETE FROM marker_cycle WHERE marker_id = :id')
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

        $sql->prepare('SELECT * FROM marker_cycle WHERE marker_id = :id')
            ->bindValue(':id', $id)
            ->execute();

        if ( !($data = $sql->fetch()) ) {
            return null;
        }

		$this->nbAvailable = intval( $data['nb_available'] );
		$this->total = intval( $data['total'] );

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

        $sql->prepare('INSERT INTO marker_cycle (marker_id,nb_available,total) VALUES (:marker_id,:nb_available,:total) ON DUPLICATE KEY UPDATE nb_available=:nb_available, total=:total')
            ->bindValue(':marker_id', $this->id)
            ->bindValue(':nb_available', $this->nbAvailable)
            ->bindValue(':total', $this->total)
            ->execute();

		return $this;
	}



    // ==============================================================
	// send data to client
	// ==============================================================
	public function getData() {
		return array_merge(parent::getData(), array(
			'nbAvailable'   => $this->nbAvailable,
			'total'         => $this->total,
		));
	}
}