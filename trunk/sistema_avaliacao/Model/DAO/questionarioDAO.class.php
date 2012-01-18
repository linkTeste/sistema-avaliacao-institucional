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
	private $host = "mysql01-farm26.kinghost.net";
	private $db_name = "faculdadeunica05";
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
		$data_criacao = date('Y-m-d H:i:s');
		
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->table." (descricao, instrumento_id, data_criacao) VALUES (:descricao, :instrumento_id, :data_criacao)");

		// Fazendo o binding
		$stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR, 128);
		$stmt->bindParam(":instrumento_id", $instrumento_id, PDO::PARAM_INT);
		$stmt->bindParam(":data_criacao", $data_criacao, PDO::PARAM_INT);

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
	public function remove($id) {
		$where = " WHERE id = ".$id;
		$sql = "DELETE FROM ".$this->table.$where;
		$result = $this->pdo;
		$result->beginTransaction();
		if ($result->exec($sql)){
			$result->commit();
		}else{
			$result->rollBack();
		}		
		
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
		$where = " WHERE id = ".$id;
		$questionario = new questionario();

		//faz o select e busca o registro no banco
		$sql = "SELECT id, descricao, instrumento_id FROM ".$this->table.$where;

		//percorre o resultset e pega o registro
		$result = $this->pdo->query($sql)->fetch();
// 		$questionario = $this->pdo->query($sql)->fetchObject(questionario);
		
		
		$descricao;
		$instrumento_id;
		//
		
		$id = $result["id"];
		$descricao = $result["descricao"];
		$instrumento_id = $result["instrumento_id"];
			
		$questionario->setId($id);
		$questionario->setDescricao($descricao);
		$questionario->setInstrumento_id($instrumento_id);
		
		return $questionario;
	}


	//list
	/**
	* @name listAll
	* @author Fabio Baнa
	* @since 16/01/2012 16:54:43
	* Lista todo os questionбrios do banco
	**/
	public function listAll($ordem = null) {
		if($ordem == "desc" || $order == null){
			$order = " ORDER BY id DESC";
		}
		if($ordem == "asc"){
			$order = " ORDER BY id ASC";
		}
		
		//$list_questionario = array();
		$sql = "SELECT * FROM ".$this->table.$order;
		$list_questionario = $this->pdo->query($sql)->fetchAll();
		
		return $list_questionario;
	}

	//connect

	/**
	 * @name connect
	 * @author Fabio Baнa
	 * @since 12/01/2012
	 * funзгopara conexao com o banco de dados
	 **/
	public function connect() {
		$this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->password);
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
		
		$stmt = $this->pdo->prepare("UPDATE ".$this->table." SET descricao = :descricao,
		instrumento_id = :instrumento_id WHERE id = :id");
		
		// Fazendo o binding
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR, 128);
		$stmt->bindParam(":instrumento_id", $instrumento_id, PDO::PARAM_INT);
		
		// Executando a SQL com os valores definidos com binding
		$consultou = $stmt->execute();
		
		return true;
		
	}



}




?>