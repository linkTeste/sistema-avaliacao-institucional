<?php
/**
 * @name questionarioDAO
 * @author Fabio Baнa
 * @since 12/01/2012
 * DAO do questionario
 */
class questionarioDAO{
	private $table = "questionario";
	private $pdo;
	private $UNSAVED_ID = 0;
	
	//database
	private $dsn = "mysql:host=mysql01-farm26.kinghost.net;port=3606;dbname=faculdadeunica05";
	private $user = "faculdadeunica05";
	private $password = "avaliacaounicampo159";

	//construtor
	function __construct(){
		$this->connect();
	}

	//add
	/**
	 * @name add
	 * @author Fabio Baнa
	 * @since 11/01/2012
	 * adiciona um novo questionario no bd
	 **/
	public function add(questionario $questionario) {
		$descricao = $questionario->getDescricao();
		$instrumento_id = $questionario->getInstrumento_id();

		$stmt = $pdo->prepare("INSERT INTO ".$this->table." (descricao, instrumento_id) VALUES (:descricao, :instrumento_id)");

		// Fazendo o binding
		$stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR, 128);
		$stmt->bindParam(":instrumento_id", $instrumento_id, PDO::PARAM_INT);

		// Executando a SQL com os valores definidos com binding
		$consultou = $stmt->execute();

		return true;

	}

	//persiste
	/**
	 * @name persiste
	 * @author Fabio Baнa
	 * @since 12/01/2012
	 * funзгo que persiste um objeto no banco, criando um novo objeto ou atualizando quando necessario
	 **/
	public function persiste(questionario $questionario) {
		$id = $questionario->getId();

		if($questionario->getId() == $this->UNSAVED_ID){
			$this->add($questionario);
		}else{
			$this->update($questionario);
		}
	}

	//remove
	/**
	 * @name remove
	 * @author Fabio Baнa
	 * @since 12/01/2012 23:59:32
	 * insert a description here
	 **/
	public function remove($param) {
		;
	}

	//list
	/**
	 * @name listar
	 * @author Fabio Baнa
	 * @since 13/01/2012 00:00:35
	 * insert a description here
	 **/
	public function listar($param) {
		$lista;

		//terminar depois
		return $lista;
	}



	//obtem
	/**
	 * @name get
	 * @author Fabio Baнa
	 * @since 12/01/2012
	 * obtem um questionario do banco baseado em um id
	 **/
	public function get($id) {
		$questionario = new questionario();

		//faz o select e busca o registro no banco
		$sql = "SELECT * from ".$this->table." WHERE id = ".$id;

		//percorre o resultset e pega o registro

		//seta as propriedades do objeto

		//return o objeto - unique result
		return $questionario;
	}


	//list

	//connect

	/**
	 * @name connect
	 * @author Fabio Baнa
	 * @since 12/01/2012
	 * funзгopara conexao com o banco de dados
	 **/
	public function connect($param) {
		$this->pdo = new PDO($this->dsn, $this->user, $this->password);
	}


	//update
	/**
	 * @name update
	 * @author Fabio Baнa
	 * @since 11/01/2012
	 * atualiza um questionario no bd
	 **/
	public function update(questionario $questionario) {
		$id = $questionario->getId();
		$descricao = $questionario->getDescricao();
		$instrumento_id = $questionario->getInstrumento_id();
		
		$stmt = $this->pdo->prepare("UPDATE ".$this->table." SET descricao = ".$descricao.",
		instrumento_id = ".$instrumento_id." WHERE id = :id");
		
		// Fazendo o binding
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR, 128);
		$stmt->bindParam(":instrumento_id", $instrumento_id, PDO::PARAM_INT);
		
		// Executando a SQL com os valores definidos com binding
		$consultou = $stmt->execute();
		
		return true;
		
	}



}




?>