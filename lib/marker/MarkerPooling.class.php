<?php

include_once __DIR__ . '/MarkerObject.class.php';

class MarkerPooling extends MarkerObject {
	protected $lastStation;
	protected $schedule;
	protected $type;

	function __construct() {
		parent::__construct();
        $this->categorie = '';
        $this->ninseeCode =  0;
        $this->nbreplac = 0;
        $this->typeAir = '';
        $this->coteRoute = 0;
        $this->route = '';
;	}



	// ==============================================================
	// create
	// ==============================================================
	public function create($arg) {
        parent::create($arg);
        $this->categorie = empty($arg['categori']) ? null : $arg['categori'];
        $this->ninseeCode = empty($arg['ninseeCode']) ? null : $arg['ninseeCode'];
        $this->nbreplac = empty($arg['nbreplac']) ? null : $arg['nbreplac'];
        $this->typeAir =empty($arg['typeAir']) ? null : $arg['typeAir'];
        $this->coteRoute =empty($arg['coteRoute']) ? null : $arg['coteRoute'];
        $this->route = empty($arg['route']) ? null : $arg['route'];
        $this->save(false);
	}



	// ==============================================================
	// delete
	// ==============================================================
	public function delete() {
        parent::delete();

		$sql = new Sql();
        return  $sql->prepare('DELETE FROM marker_pooling WHERE marker_id = :id')
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

        $sql->prepare('SELECT * FROM marker_pooling WHERE marker_id = :id')
            ->bindValue(':id', $id)
            ->execute();

        if ( !($data = $sql->fetch()) ) {
            return null;
        }

        $this->categorie = $data['categorie'] ;
        $this->ninseeCode = intval( $data['ninseeCode'] );
        $this->nbreplac = intval( $data['nbreplac'] );
        $this->typeAir = $data['typeAir'] ;
        $this->coteRoute = intval( $data['coteRoute'] );
        $this->route = '';$data['route'] ;

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

        $sql->prepare('INSERT INTO marker_pooling (categorie,ninseeCode,nbreplac,typeAir,coteRoute,route) VALUES (:categorie,:ninseeCode,:nbreplac,:typeAir,:coteRoute,:route) ON DUPLICATE KEY UPDATE categorie=:categorie, ninseeCode=:ninseeCode, nbreplac=:nbreplac, typeAir=:typeAir, coteRoute=:coteRoute, route=:route')
            ->bindValue(':categorie', $this->categorie)
            ->bindValue(':ninseeCode', $this->ninseeCode)
            ->bindValue(':nbreplac', $this->nbreplac)
            ->bindValue(':typeAir', $this->typeAir)
            ->bindValue(':coteRoute', $this->coteRoute)
            ->bindValue(':route', $this->route)

            ->execute();

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