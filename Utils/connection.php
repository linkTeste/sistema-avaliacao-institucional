<?php
/**
 * @name connection
 * @author Fabio Baía
 * @since 13/01/2012
 * insert a description here
 */
class connection extends PDO{
	private $dsn = "mysql:host=mysql01-farm26.kinghost.net;port=3606;dbname=faculdadeunica05";
	private $user = "faculdadeunica05";
	private $password = "avaliacaounicampo159";
	public $handle = null;
	function __construct() {
		try {
			//aqui ela retornará o PDO em si, veja que usamos parent::_construct()
			if ( $this->handle == null ) {
				$dbh = parent::__construct( $this->dsn , $this->user , $this->password );
				$this->handle = $dbh;
				return $this->handle;
			}
		}
		catch ( PDOException $e ) {
			echo "Conexão falhou. Erro: " . $e->getMessage( );
			return false;
		}
	}
	//aqui criamos um objeto de fechamento da conexão
	function __destruct( ) {
		$this->handle = NULL;
	}
}
?>