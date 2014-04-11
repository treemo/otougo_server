<?php
include_once __DIR__ . '/DataBase.class.php';

class Sql 
{
	private $pdo;
	private $typeRetour;
	private $requette;
	private $nomFiltre;
    
    private static $pdoCache = array();

	public function __construct( $nomConnexionSql = '', $typeRetour = PDO::FETCH_ASSOC)
	{
		$this->pdo = DataBase::getInstance( $nomConnexionSql );

		if ( empty($this->pdo ) )
		{
			//debug_print_backtrace();
			die('Connexion introuvable');
		}

		$this->typeRetour = $typeRetour;
		$this->logErreurActive = true;
		$this->nomFiltre = '';
	}

	public function update( $table, $valeur, $where = '')
	{
//		$this->pdo
	}

	public function appliquerFiltre( $query )
	{
		$addWhere = true;

		if ( !empty( $this->nomFiltre ))
		{
			if ( stripos($query, 'where') !== false )
				$addWhere = false;

			$query .= Filtre::getInstance( $this->nomFiltre )->getFiltreMySQL( $addWhere );

			$query .= Filtre::getInstance( $this->nomFiltre )->getFiltreMySQLTrieAlphabetique();
		}

		return $query;
	}

	public function closeCursor()
	{
		return $this->requette->closeCursor();
	}

	public function bindColumn($colone, &$var, $type = '')
	{
		if ( $this->requette )
		{
			if (!empty($type))
				$this->requette->bindColumn($colone, $var, $type);
			else
				$this->requette->bindColumn($colone, $var);
		}

		return $this;
	}

	public function bindValue($nom, $valeur)
	{
		if ( $this->requette )
			$this->requette->bindValue($nom, $valeur);

		return $this;
	}

	public function debug()
	{
		if ( $this->requette )
			$this->requette->debugDumpParams();
		else
			var_dump( $this );

		return $this;
	}

	public function errorInfo()
	{
		return $this->requette->errorInfo();
	}

	public function exec( $query )
	{
		$query = $this->appliquerFiltre( $query );

		return $this->pdo->exec( $query ) or $this->logErreur();
	}

	public function execute()
	{
		if ( $this->requette && !$this->requette->execute() )
		{
			$this->logErreur();
			return false;
		}

		return $this;
	}

	public function fetch($arg = '')
	{
		$retour = false;

		if ( $this->requette )
		{
			if ( empty($arg) )
				$arg = $this->typeRetour;

			$retour = $this->requette->fetch( $arg );

			if ($retour === false)
				$this->logErreur();
		}

		return $retour;
	}

	public function fetchAll()
	{
		if ( $this->requette )
			return $this->requette->fetchAll( $this->typeRetour );
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}

	protected function logErreur()
	{
		if ( !$this->logErreurActive )
			return;

		$erreur = $this->errorInfo();

		if ( !empty($erreur[0]) && $erreur[0] !== '00000')
		{
            var_dump($erreur);
			$this->requette->debugDumpParams(); // affichage du debug
	//		Erreur::getInstance()->addLogSql('SQL', serialize($erreur));
		}
	}

	public function prepare( $query )
	{
		$query = $this->appliquerFiltre( $query );

        if (!empty(self::$pdoCache[ $query ])) {
           $this->requette = self::$pdoCache[ $query ];
        }
        else {
            $this->requette = $this->pdo->prepare( $query );
            self::$pdoCache[ $query ] = $this->requette;
        }

		if ( !empty( $this->nomFiltre ))
			Filtre::getInstance( $this->nomFiltre )->bindValeurFitre( $this );

		return $this;
	}

	public function query( $query )
	{
		$query = $this->appliquerFiltre( $query );

		$this->requette = $this->pdo->query( $query );

		if ( $this->requette )
			return $this->fetchAll();
		
		$this->logErreur();
		return array();
	}

	public function rowCount()
	{
		return $this->requette->rowCount();
	}

	public function setFiltre($nomFiltre)
	{
		$this->nomFiltre = $nomFiltre;

		return $this;
	}

	public function setLogErreur( $on = true)
	{
		$this->logErreurActive = $on;

		return $this;
	}
}